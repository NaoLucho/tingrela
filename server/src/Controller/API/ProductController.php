<?php
namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use App\Entity\Product;
use FOS\RestBundle\View\View;


class ProductController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"product"})
     * @Rest\Get("/products")
     */
    public function getProducts(Request $request)
    {
        $products = $this->get('doctrine.orm.entity_manager')
                ->getRepository('App:Product')
                ->findAll();

        return $products;
    }

    /**
     * @Rest\View(serializerGroups={"product"})
     * @Rest\Get("/products/{id}")
     */
    public function getProduct(Request $request)
    {
        $product = $this->get('doctrine.orm.entity_manager')
                ->getRepository('App:Product')
                ->find($request->get('id')); // L'identifiant en tant que paramétre n'est plus nécessaire
        /* @var $product Product */

        if (empty($product)) {
            //return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            return productNotFound();
        }

        return $product;
    }

    /**
     * @Rest\View(serializerGroups={"product"})
     * @Rest\Get("/categories/{id}/products")
     */
    public function getProductsbyCategory(Request $request)
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
        return View::create(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
    }


    private function productNotFound()
    {
        return View::create(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
    }
}
