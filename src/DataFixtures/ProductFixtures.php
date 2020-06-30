<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private $categoryRepository;

    public function __construct( categoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager)
    {

        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        // on créé 10 adresses
        for ($i = 1; $i <= 500; $i++) {
            $product = new Product();

            $product->setName("Produit-".$faker->firstNameMale);
            $product->setCategory($this->categoryRepository->find(rand(1,20)));
            

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function loadCompany() {

    }

    public function getDependencies()
    {
        return [
            AddressFixtures::class,
        ];
    }
}
