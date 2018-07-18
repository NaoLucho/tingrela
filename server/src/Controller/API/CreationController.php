<?php
namespace App\Controller\API;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Creation;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class CreationController extends FOSRestController
{
    /**
     * @FOSRest\Get("/creations")
     * 
     * @return array
     */
    public function getCreations(Request $request)
    {
        $creations = $this->getDoctrine()->getRepository(Creation::class)->findAll();

        return View::create($creations, Response::HTTP_OK);
    }

}
