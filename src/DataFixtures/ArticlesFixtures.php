<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        

          $continent = ['Afrique','Amerique du Nord', 'Amerique du Sud', ' Asie', 'Europe', 'Oceanie'];

          foreach ($continent as $value) {
            $category = new Category();
          $category->setTitle($value);

          $manager->persist($category);
          }
     
        $manager->flush();
    }
}
