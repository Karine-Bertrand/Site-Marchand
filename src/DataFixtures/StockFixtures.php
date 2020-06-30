<?php

namespace App\DataFixtures;


use App\Entity\Stock;
use App\Repository\CompanyRepository;
use App\Repository\StockRepository;
use App\Repository\ProductRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class StockFixtures extends Fixture implements DependentFixtureInterface
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository, StockRepository $stockRepository, CompanyRepository $companyRepository)
    {
        $this->productRepository = $productRepository;
        $this->stockRepository = $stockRepository;
        $this->companyRepository = $companyRepository;
    }

    public function load(ObjectManager $manager)
    {

        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        // on créé 1000 stocks
        for ($i = 0; $i < 1000; $i++) {
            $stock = new Stock();

            $stock->setPrice(rand(1,10));
            $stock->setQuantity(rand(10,100));
            $stock->setValidated(true);
            $stock->setConditioning("kg");
            $stock->setCompany($this->companyRepository->find(rand(1,100)));
            $stock->setProduct($this->productRepository->find(rand(1,500)));


            

            $manager->persist($stock);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
            CompanyFixtures::class,
        ];
    }
}
