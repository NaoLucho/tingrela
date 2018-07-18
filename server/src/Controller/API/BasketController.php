<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use App\Entity\Product;
use FOS\RestBundle\View\View;

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
}
