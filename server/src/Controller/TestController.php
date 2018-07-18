<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /** 
     * @Route("/test")
     */
    public function index()
    {

        return new Response(
            '<html><body>test</body></html>'
        );
    }
}