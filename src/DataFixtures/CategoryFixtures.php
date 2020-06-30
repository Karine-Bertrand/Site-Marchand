<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        // on créé 10 categorys
        for ($i = 1; $i <= 20; $i++) {
            $category = new Category();

            $category->setName("Catégorie-".$faker->firstNameFemale);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
