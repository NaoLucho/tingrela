<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Category;
use AppBundle\Entity\Type;
use AppBundle\Entity\Product;
use AppBundle\Entity\EcommerceConfig;

// commande: php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/LoadFixtures.php

class LoadFixtures implements FixtureInterface
{
   public function load(ObjectManager $manager)
   {
	  $typeChocolats = new Type();
	  $typeChocolats->setType('chocolats');
	  $typeChocolats->setDescription('Description type Chocolats');

	  // $typeChocolats = $manager//$this->get('doctrine.orm.entity_manager')
	  //   ->getRepository('AppBundle:Type')
	  //   ->find(3);
	  //   //->findOneBy(array('type' => 'Chocolats'));

	  $category1 = new Category();
	  $category1->setCategory('Les assortiments de chocolats');
	  $category1->setDescription('Description assortiments de chocolats');
	  $category1->setType($typeChocolats);

	  $category2 = new Category();
	  $category2->setCategory('Les buissons');
	  $category2->setDescription('Description buissons');
	  $category2->setType($typeChocolats);

	  $category3 = new Category();
	  $category3->setCategory('Les mendiants');
	  $category3->setDescription('Description mendiants');
	  $category3->setType($typeChocolats);

	  $category4 = new Category();
	  $category4->setCategory('Les orangettes & citronettes');
	  $category4->setDescription('Description orangettes & citronettes');
	  $category4->setType($typeChocolats);

	  $typeConfiseries = new Type();
	  $typeConfiseries->setType('confiseries');
	  $typeConfiseries->setDescription('Description type Confiseries');

	  // $typeConfiseries = $manager//$this->get('doctrine.orm.entity_manager')
	  //   ->getRepository('AppBundle:Type')
	  //   ->findOneBy(array('type' => 'Confiseries'));

	  $category5 = new Category();
	  $category5->setCategory('Les berlingots');
	  $category5->setDescription('Description berlingots');
	  $category5->setType($typeConfiseries);

	  $category6 = new Category();
	  $category6->setCategory('Les guimauves');
	  $category6->setDescription('Description guimauves');
	  $category6->setType($typeConfiseries);

	  $category7 = new Category();
	  $category7->setCategory('Les nougats');
	  $category7->setDescription('Description nougats');
	  $category7->setType($typeConfiseries);

	  $category8 = new Category();
	  $category8->setCategory('Les pâtes de fruits');
	  $category8->setDescription('Description pâtes de fruits');
	  $category8->setType($typeConfiseries);


	  $product1 = new Product();
	  $product1->setName("Produit chocolat 1");
	  $product1->setDescription("Chocolat 1 dans categorie: Assortiments de chocolats");
	  $product1->setPrice(10);
	  $product1->setStock(100);
	  //$product1->addCategory($category1); //NOT working!
	  $category1->addProduct($product1);

	  $product2 = new Product();
	  $product2->setName("Produit chocolat 2");
	  $product2->setDescription("Chocolat 2 dans categorie: Assortiments de chocolats");
	  $product2->setPrice(5);
	  $product2->setStock(8);
	  //$product2->addCategory($category1);
	  $category1->addProduct($product2);

	  $product3 = new Product();
	  $product3->setName("Produit guimauve orangette");
	  $product3->setDescription("Un peu de guimauve et un peu de chocolat orangé");
	  $product3->setPrice(5);
	  $product3->setStock(8);
	  // $product3->addCategory($category6);
	  // $product3->addCategory($category4);
	  $category4->addProduct($product3);
	  $category6->addProduct($product3);


	  $manager->persist($typeChocolats);
	  $manager->persist($typeConfiseries);
	  $manager->persist($category1);
	  $manager->persist($category2);
	  $manager->persist($category3);
	  $manager->persist($category4);
	  $manager->persist($category5);
	  $manager->persist($category6);
	  $manager->persist($category7);
	  $manager->persist($category8);
	  $manager->persist($product1);
	  $manager->persist($product2);
	  $manager->persist($product3);


	  $tva = new EcommerceConfig();
	  $tva->setTva(0.2);
	  $manager->persist($product3);
	  $manager->flush();
   }
}
