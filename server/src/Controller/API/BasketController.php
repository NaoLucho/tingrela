<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\View;
use App\Entity\Product;
use App\Entity\Commande;
use App\Entity\CommandeBasket;
use App\Entity\Customer;
use App\Form\Type\CommandeType;
use App\Services\StripeClient;
use App\Services\Mailer;

class BasketController extends Controller
{
  private $commandId;

  /**
   * @Rest\Get("/basket/products")
   */
  public function getBasket(Request $request)
  {
    $products = array();
    if($request->query->get('productsid'))
    {
      $tabIds = explode(";",$request->query->get('productsid'));

      $products = $this->getDoctrine()->getRepository('App:Product')->findArray($tabIds);
    }

    return View::create($products, Response::HTTP_OK);
  }

  /**
   * @Rest\Post("/payment")
   */
  public function postPayment(Request $request, StripeClient $stripeClient)
  {
    $errorMessage = "";

    $requestContent = json_decode($request->getContent(), true);

    $amount = $requestContent['amount'];
    if ((0+$amount)<=0) {
      return View::create('Montant à payer invalide', 402);
    }

    $commandeReq = $requestContent['commande'];
    $commandeReq['address'] = $commandeReq['adresse'];
    unset($commandeReq['adresse']);

    // Check Commande validity and save it with products links and customer ID (tokenStripe)
    $token = $requestContent['token'];
    // dump($token);
    //if(!empty($token))
    
    $em = $this->get('doctrine.orm.entity_manager');

    $customer = $em->getRepository(Customer::class)->findOneBy(["email" => $commandeReq["email"]]);

    if ( !$customer ) {
      $customer = new Customer();
      $em->persist($customer);
    }

    $customer->setFirstName($commandeReq["firstname"]);
    $customer->setLastName($commandeReq["lastname"]);
    $customer->setEmail($commandeReq["email"]);
    if ( isset($commandeReq["phone"]) ) {
      $customer->setPhone($commandeReq["phone"]);
    }
    $customer->setAddress($commandeReq["address"]);
    $customer->setPostalCode($commandeReq["postalcode"]);
    $customer->setCity($commandeReq["city"]);

    $commande = new Commande();
    $commande->setAddress($commandeReq["address"]);
    $commande->setPostalCode($commandeReq["postalcode"]);
    $commande->setCity($commandeReq["city"]);

    if ( isset($commandeReq['comment']) ) {
      $commande->setComment($commandeReq['comment']);
    }
    $commande->setCustomer($customer);
    $commande->setReference($token);
    $em->persist($commande);

    //Amount calculer ici avec les produits du panier pour vérifier qu'il n'y a pas eu de changement ou erreur coté client
    $amountCheck = 0;
    $ecommerceConfig = $em->getRepository('App:EcommerceConfig')->find(1);
    // $tva = $ecommerceConfig->getTva();

    
    $basketReq = $requestContent['basket'];

    $em = $this->get('doctrine.orm.entity_manager');
    if(count($basketReq) >0)
    {
      /* for ($id=0; $id < count($basketReq); $id++) { */
      foreach($basketReq as $item) {
        
        $commandProduct = new CommandeBasket();
        $commandProduct->setCommande($commande);
        $product = $em->getRepository('App:Product')->find($item['id']);
        if ($product) {
          $amountCheck = $amountCheck + $product->getPrice() * floatval($item['qte']) * ( 1 + $product->getTva()->getTva() ) / $product->getStep(); 

          $commandProduct->setProduct($product);
          $commandProduct->setQuantity($item['qte']);
          $em->persist($commandProduct);
        }
      };

      if((float)$amountCheck != $amount) {

        return View::create("Le montant du panier a un problème. Contactez directement notre boutique pour régler le problème.", 402);
      }

      $commande->setTotal($amountCheck);
      
    }
    
    // Send charge payment to Stripe
    /* $stripeClient = $this->get('flosch.stripe.client'); *//* 
    $customer = $stripeClient->createCustomer($token, $commandeReq['firstname'] . ' ' . $commandeReq['lastname']); */
    /*  $payment = $stripeClient->createCharge($amount*100, 'eur', $token, null, 0, 0, [ $commandeReq['firstname'] . ' ' . $commandeReq['lastname'] ]); */
    $em->flush();
    $commandId = $commande->getId();
    $this->sendReceiptEmail($commandId);

    try {
      $response = $stripeClient->createCharge($token, $amount*100, $commandeReq['email']);
      $commande->setValidated(true);
      $em->flush();
      $this->sendValidationEmail($commandId, true);
      return View::create(array(
        "message"=> "Achat validé", 
        "total"=> $amountCheck, 
        "ref"=> uniqid() . $commandId,
        "dataValidated" => true
      ), Response::HTTP_OK);
    }
    
    catch (\Stripe\Error\Base $e) {
      /* $this->addFlash('warning', sprintf('Unable to take payment, %s', $e instanceof \Stripe\Error\Card ? lcfirst($e->getMessage()) : 'please try again.')); */
      $response = $e->getMessage();
      $commande->setValidated(false);
      $commande->setError($response);
      $em->flush();
      $this->sendValidationEmail($commandId, false);
      return View::create(array(
        "dataValidated" => false,
        "ref" =>uniqid() . $commandId,
      ), Response::HTTP_OK);
    } 
    
    /* return View::create($payment, Response::HTTP_OK); */

  }

  public function sendReceiptEmail($commandId)
  {

    $em = $this->get('doctrine.orm.entity_manager');

    $command = $em->getRepository('App:Commande')->find($commandId);
    $customer= $command->getCustomer()->getEmail();
    $ecommerceConfig = $em->getRepository('App:EcommerceConfig')->find(1);
    $commandBasket = $em->getRepository('App:CommandeBasket')->findBy(['commande' => $command->getId()]);

    $message = (new \Swift_Message('Votre commande de chocolats'))
        ->setFrom($this->getParameter('user_email'))
        ->setTo($customer)
        ->setSubject('Commande Julien Gayraud Chocolatier Confiseur')
        ->setBody(
             $this->renderView(
                // templates/emails/registration.html.twig
                'emails/bill.html.twig',
                array('command' => $command, 'basket' => $commandBasket)
            ),
            'text/html'
        )
    ;

    $transport = (new \Swift_SmtpTransport($this->getParameter('mailer_host'), $this->getParameter('mailer_port')))
      ->setUsername($this->getParameter('user_email'))
      ->setPassword($this->getParameter('user_email_pass'))
    ;

    $mailer = new \Swift_Mailer($transport);
    // Envoie du récap au client
    $mailer->send($message);
  }

  public function sendValidationEmail($commandId, $checker)
  {

    $em = $this->get('doctrine.orm.entity_manager');

    $command = $em->getRepository('App:Commande')->find($commandId);
    $customer= $command->getCustomer()->getEmail();
    $ecommerceConfig = $em->getRepository('App:EcommerceConfig')->find(1);
    $commandBasket = $em->getRepository('App:CommandeBasket')->findBy(['commande' => $command->getId()]);

    $message = (new \Swift_Message('Votre commande de chocolats'))
        ->setFrom($this->getParameter('user_email'))
        ->setTo($customer);
        if( $checker == false) {
          $message->setSubject('Paiement refusé');
        } else {
          $message->setSubject('Commande validée et en cours de réalisation');
        }
        $message->setBody(
             $this->renderView(
                // templates/emails/registration.html.twig
                'emails/validation.html.twig',
                array('command' => $command, 'basket' => $commandBasket, 'checker' => $checker)
            ),
            'text/html'
        )
    ;

    $transport = (new \Swift_SmtpTransport($this->getParameter('mailer_host'), $this->getParameter('mailer_port')))
      ->setUsername($this->getParameter('user_email'))
      ->setPassword($this->getParameter('user_email_pass'))
    ;

    $mailer = new \Swift_Mailer($transport);
    // Envoie du récap au client
    $mailer->send($message);

    // Envoie du récap à Julien pour faire la commande
    if($checker == true) {
      $message->setTo($this->getParameter('user_email'));
      $mailer->send($message);
    }
  }
}
