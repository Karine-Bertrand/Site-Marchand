<?php

namespace App\DataFixtures;

use App\Entity\Drive;
use App\Repository\AddressRepository;
use App\Repository\CompanyRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class DriveFixtures extends Fixture implements DependentFixtureInterface
{
    private $addressRepository;

    public function __construct( AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function load(ObjectManager $manager)
    {

        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        // on créé 10 adresses
        for ($i = 1; $i <= 100; $i++) {
            $drive = new Drive();

            $drive->setName($faker->company);
            $drive->setAddress($this->addressRepository->find($i));
            

            $manager->persist($drive);
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
