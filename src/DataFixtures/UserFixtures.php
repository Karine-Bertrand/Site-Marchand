<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\AddressRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
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
        for ($i = 0; $i <= 500; $i++) {
            $user = new User();

            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setFirstname($faker->firstname);
            $user->setLastname($faker->lastname);
            $user->setAddress($this->addressRepository->find(rand(1,1000)));
            $user->setIsVerified(rand(0,1));
            

            $manager->persist($user);
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
