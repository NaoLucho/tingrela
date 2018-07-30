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

class BasketController extends Controller
{
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
  public function payment(Request $request, StripeClient $stripeClient)
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
    $tva = $ecommerceConfig->getTva();

    
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
          $amountCheck = $amountCheck + $product->getPrice() * floatval($item['qte']) / $product->getStep(); 

          $commandProduct->setProduct($product);
          $commandProduct->setQuantity($item['qte']);
          $em->persist($commandProduct);
        }
      };

      $amountCheck = $amountCheck+$amountCheck*$tva;

      if((float)$amountCheck != $amount) {

        return View::create("Le montant du panier a un problème. Contactez directement notre boutique pour régler le problème.", 402);
      }

      $commande->setTotal($amountCheck);
      
    }
    
    // Send charge payment to Stripe
    /* $stripeClient = $this->get('flosch.stripe.client'); *//* 
    $customer = $stripeClient->createCustomer($token, $commandeReq['firstname'] . ' ' . $commandeReq['lastname']); */
    /*  $payment = $stripeClient->createCharge($amount*100, 'eur', $token, null, 0, 0, [ $commandeReq['firstname'] . ' ' . $commandeReq['lastname'] ]); */
    
    try {
      $response = $stripeClient->createCharge($token, $amount*100);
      $commande->setValidated(true);
      $em->flush();
      return View::create('Achat validé', Response::HTTP_OK);
    }
    
    catch (\Stripe\Error\Base $e) {
      /* $this->addFlash('warning', sprintf('Unable to take payment, %s', $e instanceof \Stripe\Error\Card ? lcfirst($e->getMessage()) : 'please try again.')); */
      $response = $e->getMessage();
      $commande->setValidated(false);
      $em->flush();
      return View::create($e, 402);
    } 
    
    /* return View::create($payment, Response::HTTP_OK); */

  }
}
