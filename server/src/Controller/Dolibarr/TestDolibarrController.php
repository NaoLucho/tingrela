<?php

namespace App\Controller\Dolibarr;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestDolibarrController extends AbstractController
{
    /**
     * @Route("/test/dolibarr", name="test_dolibarr")
     */
    public function index()
    {
        
        return $this->render('test_dolibarr/index.html.twig', [
            'controller_name' => 'TestDolibarrController',
        ]);
    }
}
