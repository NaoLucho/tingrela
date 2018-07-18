<?php

namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Entity\Commande;
use AppBundle\Entity\CommandeBasket;
use AppBundle\Form\Type\CommandeType;

use Stripe\Stripe;

use Symfony\Component\HttpKernel\Exception\HttpException;


class PaymentController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"commande"})
     * @Rest\Post("/payment")
     */
    public function paymentAction(Request $request)
    {
      $errorMessage = "";

      $amount = $request->request->get('amount');
      
      if ((0+$amount)<=0) {
        return 'Montant à payer invalide';
      }

      $commandeReq = $request->request->get('commande');

      // Check Commande validity and save it with products links and customer ID (tokenStripe)
      $token = $request->request->get('token');
      // dump($token);
      //if(!empty($token))
      
      $em = $this->get('doctrine.orm.entity_manager');

      $commande = new Commande();
      $form = $this->createForm(CommandeType::class, $commande);

      //Amout calculer ici avec les produits du panier pour vérifier qu'il n'y a pas eu de changement ou erreur coté client
      $amountCheck = 0;
      $ecommerceConfig = $em->getRepository('AppBundle:EcommerceConfig')->find(1);
      $tva = $ecommerceConfig->getTva();

      $form->submit($commandeReq); // Validation des données
      // dump($commande);
      if (!$form->isValid()) {
        return $form;
      }
      else
      {
        //TEMPORAIRE: Doctrine n'accepte pas null dans le champ comment, par defaut:"";
        if($commande->getComment() == null)
          $commande->setComment("");
        

        $commande->setReference($token['id']);
        $em->persist($commande);
        
        $basketReq = $request->request->get('basket');
        
        // dump($commande);
       //  dump($basketReq);

        $em = $this->get('doctrine.orm.entity_manager');
        if(count($basketReq) >0)
        {
          /* for ($id=0; $id < count($basketReq); $id++) { */
          foreach($basketReq as $item) {
            
            $commandProduct = new CommandeBasket();
            $commandProduct->setCommande($commande);
            $product = $em->getRepository('AppBundle:Product')->find($item['id']);
            $amountCheck = $amountCheck + $product->getPrice() * floatval($item['qte']) / $product->getPas(); 

            $commandProduct->setProduct($product);
            $commandProduct->setQuantity($item['id']);
            $em->persist($commandProduct);
          };
        }

        $amountCheck = $amountCheck+$amountCheck*$tva;

        if((float)$amountCheck != $amount) {

          return "Montant à payer demandé (".$amount.") différent du prix des produits du panier.(".$amountCheck.")";
        }
        
        $em->flush();
      }

      // Send charge payment to Stripe

      //$commande2 = $em->getRepository('AppBundle:Commande')->find($commande->getId());

      // if ok return commande reference, update commande to validated
      


      // Set your secret key: remember to change this to your live secret key in production
      // See your keys here: https://dashboard.stripe.com/account/apikeys
      Stripe::setApiKey("sk_test_2WXwdMC6Qwystzz38ht6kFck"); //TEST secret

      // Token is created using Stripe.js or Checkout!
      // Get the payment token ID submitted by the form:
      $token = $token['id'];//$_POST['stripeToken'];

      // Charge the user's card:
      $err = null;
      
      try {
        // Use Stripe's library to make requests...
        $charge = \Stripe\Charge::create(array(
        "amount" => $amount,
        "currency" => "eur",
        "description" => "Paiment en direct",
        "source" => $token,
        ));
        // dump($charge);
        // PAIEMENT EFFECTUEE
        //Save status validated
        $commande->setValidated(true);
        $em->persist($commande);
        $em->flush();
        return $commande;

      // } catch(\Stripe\Error\Card $e) {
      //   // Since it's a decline, \Stripe\Error\Card will be caught
      //   $body = $e->getJsonBody();
      //   $err  = $body['error'];

      //   print('Status is:' . $e->getHttpStatus() . "\n");
      //   print('Type is:' . $err['type'] . "\n");
      //   print('Code is:' . $err['code'] . "\n");
      //   // param is '' in this case
      //   print('Param is:' . $err['param'] . "\n");
      //   print('Message is:' . $err['message'] . "\n");
      // } catch (\Stripe\Error\RateLimit $e) {
      //   // Too many requests made to the API too quickly
      //   $err = $e;
      // } catch (\Stripe\Error\InvalidRequest $e) {
      //   // Invalid parameters were supplied to Stripe's API
      //   $err = $e;
      // } catch (\Stripe\Error\Authentication $e) {
      //   // Authentication with Stripe's API failed
      //   // (maybe you changed API keys recently)
      //   $err = $e;
      // } catch (\Stripe\Error\ApiConnection $e) {
      //   // Network communication with Stripe failed
      //   $err = $e;
      // } catch (\Stripe\Error\Base $e) {
      //   // Display a very generic error to the user, and maybe send
      //   $err = $e;
      //   // yourself an email
      } catch (Exception $e) {
        // Something else happened, completely unrelated to Stripe
        $err = $e;
         return $e;
      }
      if (!empty($err))
      {
        // dump($err);
        throw new HttpException($err->getCode(), $err->getMessage());
      }
        
      return null;
    }
  }
