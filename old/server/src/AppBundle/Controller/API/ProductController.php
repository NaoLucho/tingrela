<?php
namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Form\Type\ProductType;
use AppBundle\Entity\Product;


class ProductController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"product"})
     * @Rest\Get("/products")
     */
    public function getProductsAction(Request $request)
    {
        $products = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Product')
                ->findAll();
        /* @var $product Product[] */

        return $products;
    }

    /**
     * @Rest\View(serializerGroups={"product"})
     * @Rest\Get("/products/{id}")
     */
    public function getProductAction(Request $request)
    {
        $product = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Product')
                ->find($request->get('id')); // L'identifiant en tant que paramétre n'est plus nécessaire
        /* @var $product Product */

        if (empty($product)) {
            //return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            return productNotFound();
        }

        return $product;
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"product"})
     * @Rest\Post("/admin/products")
     */
    public function postProductsAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->submit($request->request->all()); // Validation des données


        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($product);
            $em->flush();
            return $product;
        } else {
            return $form;
        }
    }

//     /** POST product basic */
//     /**
//      * @Rest\View(statusCode=Response::HTTP_CREATED)
//      * @Rest\Post("/products")
//      */
//     public function postProductsAction(Request $request)
//     {
//         $product = new Product();
//         $product->setName($request->get('name'))
//             ->setPrice($request->get('price'))
//             ->setStock($request->get('stock'))
//             ->setDescription($request->get('description'));

//         $em = $this->get('doctrine.orm.entity_manager');
//         $em->persist($product);
//         $em->flush();

//         return $product;
//     }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/admin/products/{id}")
     */
    public function removeProductAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $product = $em->getRepository('AppBundle:Product')
                    ->find($request->get('id'));
        /* @var $product Product */

        if ($product) {
            $em->remove($product);
            $em->flush();
        }
    }


    /**
     * @Rest\View(serializerGroups={"product"})
     * @Rest\Put("/admin/products/{id}")
     */
    public function updateProductAction(Request $request)
    {
        return $this->updateProduct($request, true);
    }

    /**
     * @Rest\View(serializerGroups={"product"})
     * @Rest\Patch("/admin/products/{id}")
     */
    public function patchProductAction(Request $request)
    {
        return $this->updateProduct($request, false);
    }

    private function updateProduct(Request $request, $clearMissing)
    {
        $product = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Product')
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $product Product */

        if (empty($product)) {
            //return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            return productNotFound();
        }

        $form = $this->createForm(ProductType::class, $product);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($product);
            $em->flush();
            return $product;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"product"})
     * @Rest\Get("/categories/{id}/products")
     */
    public function getProductsbyCategoryAction(Request $request)
    {
        $category = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Category')
                ->find($request->get('id')); // L'identifiant en tant que paramétre n'est plus nécessaire
                /* @var $category Category */

        if (empty($category)) {
            return $this->categoryNotFound();
        }

        return $category->getProducts();
    }

    private function categoryNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
    }


    private function productNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
    }
}
