<?php
namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Entity\Creation;

/** INFO dev: AUTO au lieu de @ permet d'indiquer que Rest fait deja le taf
* Mais ici y se transforme en ie au pluriel, ce qui n'est pas le fonctionnement automatique de Rest, donc on garde les info routage
*/
class CreationController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/creations")
     */
    public function getCreationsAction(Request $request)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Creation')->findAll();
    }
}
