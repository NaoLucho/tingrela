<?php
namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Form\Type\TypeType;
use AppBundle\Entity\Type;

/** INFO dev: AUTO au lieu de @ permet d'indiquer que Rest fait deja le taf
* Mais ici y se transforme en ie au pluriel, ce qui n'est pas le fonctionnement automatique de Rest, donc on garde les info routage
*/
class TypeController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"type"})
     * @Rest\Get("/types")
     */
    public function getTypesAction(Request $request)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Type')->findAll();
    }

    /**
     * @Rest\View(serializerGroups={"typeonly"})
     * @Rest\Get("/typesOnly")
     */
    public function getTypesOnlyAction(Request $request)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Type')->findAll();
    }

    /**
     * @Rest\View(serializerGroups={"type"})
     * @Rest\Get("/types/{id}")
     */
    public function getTypeAction(Request $request)
    {
        $type = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Type')
        ->find($request->get('id'));
        /* @var $type Type */

        if (empty($type)) {
            return new JsonResponse(['message' => 'Type not found'], Response::HTTP_NOT_FOUND);
        }

        return $type;
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"type"})
     * @Rest\Post("/admin/types")
     */
    public function postTypeAction(Request $request)
    {
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);

        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($type);
            $em->flush();
            return $type;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/admin/types/{id}")
     */
    public function removeTypeAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $type = $em->getRepository('AppBundle:Type')
                    ->find($request->get('id'));
        /* @var $product Type */

        if ($type) {
            $em->remove($type);
            $em->flush();
        }
    }

    /**
     * @Rest\View(serializerGroups={"type"})
     * @Rest\Put("/admin/types/{id}")
     */
    public function updateTypeAction(Request $request)
    {
        return $this->updateType($request, true);
    }

    /**
     * @Rest\View(serializerGroups={"type"})
     * @Rest\Patch("/admin/types/{id}")
     */
    public function patchTypeAction(Request $request)
    {
        return $this->updateType($request, false);
    }

    private function updateType(Request $request, $clearMissing)
    {
        $type = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Type')
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $type Type */

        if (empty($type)) {
            //return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            return typeNotFound();
        }

        $form = $this->createForm(TypeType::class, $type);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($type);
            $em->flush();
            return $type;
        } else {
            return $form;
        }
    }

    private function typeNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Type not found'], Response::HTTP_NOT_FOUND);
    }
}
