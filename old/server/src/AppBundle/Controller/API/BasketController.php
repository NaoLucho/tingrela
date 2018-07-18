<?php

namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Entity\Product;

class BasketController extends Controller
{



    /**
     * @Rest\View()
     * @Rest\Put("/basket/add/{productid}/{qte}", defaults={"qte" = 1})
     */
    public function addAction(Request $request)
    {
      $session = $request->getSession();

      if(!$session->has('basket'))
        $session->set('basket', array());

      $basket = $session->get('basket', array());

      //$basket[ID produit] => Quantité

      if(array_key_exists($request->get('productid'), $basket))
      {
        if($request->get('qte')!=null)
          $basket[$request->get('productid')] = $request->get('qte');
      }
      else
      {
        $basket[$productid] = $request->get('qte'); //1 by default
      }

      $session->set('basket', $basket);

        //return $this->getDoctrine()->getRepository('AppBundle:Post')->findAll();
    }

    /**
     * @Rest\View(serializerGroups={"product"})
     * @Rest\Get("/basket/products")
     */
    public function getPanierAction(Request $request)
    {
      // echo "PATATA";
      //dump($request);
      $products = array();
      if($request->query->get('productsid'))
      {
      //  //$basket = $session->get('basket', array());

        $tabIds = explode(";",$request->query->get('productsid'));
        // dump($tabIds);
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findArray($tabIds);
      }
      // $products = $this->get('doctrine.orm.entity_manager')
      //           ->getRepository('AppBundle:Product')
      //           ->findAll();
      return $products;
    }

        /**
     * @Rest\View()
     * @Rest\Get("/test")
     */
        public function getProductsAction(Request $request)
        {
          $products = $this->get('doctrine.orm.entity_manager')
          ->getRepository('AppBundle:Product')
          ->findAll();
          /* @var $product Product[] */

        //return $products;
        }

      }
