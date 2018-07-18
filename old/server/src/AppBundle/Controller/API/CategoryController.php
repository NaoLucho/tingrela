<?php
namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Form\Type\CategoryType;
use AppBundle\Entity\Category;
use AppBundle\Entity\Type;
use AppBundle\Entity\Product;

class CategoryController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"category"})
     * @Rest\Get("/categories")
     */
    public function getCategoriesAction(Request $request)
    {
        $categories = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Category')
        ->findAll();
        /* @var $category Category[] */

        return $categories;
    }

    /**
     * @Rest\View(serializerGroups={"category"})
     * @Rest\Get("/categories/{id}")
     */
    public function getCategoryAction(Request $request)
    {
        $category = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Category')
        ->find($request->get('id'));
        /* @var $category Category */

        if (empty($category)) {
            return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return $category;
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"category"})
     * @Rest\Post("/admin/categories")
     */
    public function postCategoriesAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($category);
            $em->flush();
            return $category;
        } else {
            return $form;
        }
    }

//     /** POST category basic */
//     /**
//      * @Rest\View(statusCode=Response::HTTP_CREATED)
//      * @Rest\Post("/categories")
//      */
//     public function postCategoriesAction(Request $request)
//     {
//         $category = new Category();
//         $category->setName($request->get('name'))
//             ->setPrice($request->get('price'))
//             ->setStock($request->get('stock'))
//             ->setDescription($request->get('description'));

//         $em = $this->get('doctrine.orm.entity_manager');
//         $em->persist($category);
//         $em->flush();

//         return $category;
//     }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/admin/categories/{id}")
     */
    public function removeCategoryAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $category = $em->getRepository('AppBundle:Category')
                    ->find($request->get('id'));
        /* @var $product Category */

        if ($category) {
            $em->remove($category);
            $em->flush();
        }
    }

    /**
     * @Rest\View(serializerGroups={"category"})
     * @Rest\Put("/admin/categories/{id}")
     */
    public function updateCategoryAction(Request $request)
    {
        return $this->updateCategory($request, true);
    }

    /**
     * @Rest\View(serializerGroups={"category"})
     * @Rest\Patch("/admin/categories/{id}")
     */
    public function patchCategoryAction(Request $request)
    {
        return $this->updateCategory($request, false);
    }

    private function updateCategory(Request $request, $clearMissing)
    {
        $category = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Category')
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $category Category */

        if (empty($category)) {
            //return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            return categoryNotFound($request->get('id'));
        }

        $form = $this->createForm(CategoryType::class, $category);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($category);
            $em->flush();
            return $category;
        } else {
            return $form;
        }
    }

    // DEFINIE DANS ProductController
    // /**
    //  * @Rest\View(serializerGroups={"category"})
    //  * @Rest\Get("/categories/{id}/products")
    //  */
    // public function getProductsAction(Request $request)
    // {
    //     $category = $this->get('doctrine.orm.entity_manager')
    //         ->getRepository('AppBundle:Category')
    //         ->find($request->get('id')); // L'identifiant en tant que paramétre n'est plus nécessaire
    //         /* @var $place Place */

    //     if (empty($category)) {
    //         return $this->categoryNotFound($request->get('id'));
    //     }

    //     return $category->getProducts();
    // }


    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"category"})
     * @Rest\Put("/admin/categories/{catid}/products/{prodid}")
     */
    public function addProductToCategoryAction(Request $request)
    {
        $category = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Category')
                ->find($request->get('catid'));
        if (empty($category)) {
            return categoryNotFound($request->get('catid'));
        }

        $product = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Product')
                ->find($request->get('prodid'));

        if (empty($product)) {
            return productNotFound($category, $request->get('prodid'));
        }

        // Validation des données NOT needed
        //Check if product is already added
        //$products = $category->getProducts();

        $linkExist = false;
        foreach ($category->getProducts() as $index => $elem) {
            if($elem->getId() == $product->getId())
            {
                $linkExist = true;
                break;
            }
        }
        if(!$linkExist)
        {
            $category->addProduct($product);
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($category);
            $em->flush();
            //return $category;
            return \FOS\RestBundle\View\View::create(['message' => 'Product id:'+$request->get('prodid')+' added to products of category', 'data' => $category], Response::HTTP_OK);
        }
        $data = '{ "data": "Product id:'+1+' added to products of category" }';
        $response = JsonResponse::fromJsonString($data.tostring());

        return $response;
        //return $category; //new JsonResponse(['message' => 'Category has already this product'], Response::HTTP_NO_CONTENT);
        $message = 'Product id:'+$request->get('prodid')+' is already in products of this category';
        return new JsonResponse(['message' => 'message', 'data' => $category], Response::HTTP_OK);
        return new JsonResponse(['message' => 'Product id:'+$request->get('prodid')+' is already in products of this category', 'data' => $category], Response::HTTP_OK);
    }


    /**
     * @Rest\View(serializerGroups={"category"})
     * @Rest\Delete("/admin/categories/{catid}/products/{prodid}")
     */
    public function removeProductToCategoryAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $category = $em->getRepository('AppBundle:Category')
                ->find($request->get('catid'));
        if (empty($category)) {
            return categoryNotFound($request->get('catid'));
        }

        $product = $em->getRepository('AppBundle:Product')
                ->find($request->get('prodid'));

        if (empty($product)) {
            return productNotFound($category, $request->get('prodid'));
        }

        $linkExist = false;
        foreach ($category->getProducts() as $index => $elem) {
            if($elem->getId() == $product->getId())
            {
                $linkExist = true;
                break;
            }
        }
        if($linkExist)
        {
            $category->removeProduct($product);
            $em->persist($category);
            $em->flush();
            //return $category;
            return new JsonResponse(['message' => 'Product id:'+$request->get('prodid')+' removed from list', 'data' => $category], Response::HTTP_OK);
        }
        return new JsonResponse(['message' => 'Product id:'+$request->get('prodid')+' not in products of this category', 'data' => $category], Response::HTTP_OK);
    }

    // /**
    //  * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"category"})
    //  * @Rest\Post("/categories/{catid}/types/{typeid}/add")
    //  */
    // public function addTypeToCategoryAction(Request $request)
    // {
    //     $category = $this->get('doctrine.orm.entity_manager')
    //             ->getRepository('AppBundle:Category')
    //             ->find($request->get('catid'));
    //     if (empty($category)) {
    //         return categoryNotFound($request->get('catid'));
    //     }

    //     $type = $this->get('doctrine.orm.entity_manager')
    //             ->getRepository('AppBundle:Type')
    //             ->find($request->get('typeid'));

    //     if (empty($type)) {
    //         return typeNotFound($category, $request->get('typeid'));
    //     }

    //     // Validation des données NOT needed
    //     //Check if type is already added
    //     //$products = $category->getProducts();

    //     $linkExist = false;
    //     foreach ($category->getProducts() as $index => $elem) {
    //         if($elem->getId() == $type->getId())
    //         {
    //             $linkExist = true;
    //             break;
    //         }
    //     }
    //     if(!$linkExist)
    //     {
    //         $category->setType($type);
    //         $em = $this->get('doctrine.orm.entity_manager');
    //         $em->persist($category);
    //         $em->flush();
    //         return $category;
    //     }
    //     return $category; //new JsonResponse(['message' => 'Category has already this product'], Response::HTTP_NO_CONTENT);
    // }


    // /**
    //  * @Rest\View(serializerGroups={"category"})
    //  * @Rest\Post("/categories/{catid}/types/{prodid}/remove")
    //  */
    // public function removeTypeToCategoryAction(Request $request)
    // {
    //     $em = $this->get('doctrine.orm.entity_manager');
    //     $category = $em->getRepository('AppBundle:Category')
    //             ->find($request->get('catid'));
    //     if (empty($category)) {
    //         return categoryNotFound($request->get('catid'));
    //     }

    //     $type = $em->getRepository('AppBundle:Type')
    //             ->find($request->get('typeid'));

    //     if (empty($type)) {
    //         return typeNotFound($category, $request->get('typeid'));
    //     }

    //     $category->setType(null);
    //     $em->persist($category);
    //     $em->flush();
    //     return $category;

    // }

    private function categoryNotFound($catid)
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Category id:'+$catid+' not found'], Response::HTTP_NOT_FOUND);
    }

    private function productNotFound($prodid)
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Product id:'+$prodid+' not found'], Response::HTTP_NOT_FOUND);
    }

    private function typeNotFound($typeid)
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Type id:'+$typeid+' not found'], Response::HTTP_NOT_FOUND);
    }
}
