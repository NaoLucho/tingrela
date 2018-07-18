<?php
namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
/* use Symfony\Component\HttpFoundation\JsonResponse; */
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Type;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class TypeController extends FOSRestController
{
    /**
     * @FOSRest\Get("/types")
     * 
     * @return array
     */
    public function getTypesAction(Request $request)
    {
        $types = $this->getDoctrine()->getRepository(Type::class)->findAll();

        return View::create($types, Response::HTTP_OK);
    }

/*     private function typeNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Type not found'], Response::HTTP_NOT_FOUND);
    } */
}
