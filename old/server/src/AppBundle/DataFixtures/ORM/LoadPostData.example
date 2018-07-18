<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Post;

class LoadPostData implements FixtureInterface
{
   public function load(ObjectManager $manager)
   {
      $post = new Post();
      $post->setTitle('post1');

      $post2 = new Post();
      $post2->setTitle('post2');

      $manager->persist($post);
      $manager->persist($post2);
      $manager->flush();
   }
}
