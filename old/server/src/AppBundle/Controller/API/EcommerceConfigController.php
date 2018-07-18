<?php
namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Entity\EcommerceConfig;

class EcommerceConfigController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/tva")
     */
    public function getTvaAction(Request $request)
    {
        return $this->getDoctrine()->getRepository('AppBundle:EcommerceConfig')->find(1);
    }
}
