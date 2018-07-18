<?php
namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as FOSRest; // alias pour toutes les annotations
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use App\Entity\EcommerceConfig;

class EcommerceConfigController extends FOSRestController
{
    /**
     * @FOSRest\Get("/tva")
     */
    public function getTva(Request $request)
    {
        $tva =  $this->getDoctrine()->getRepository('App:EcommerceConfig')->find(1);

        return View::create($tva, Response::HTTP_OK);
    }
}