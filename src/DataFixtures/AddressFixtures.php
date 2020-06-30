<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        // on créé 10 adresses
        for ($i = 1; $i <= 1000; $i++) {
            $adresse = new Address();

            $adresse->setCity($faker->city);
            $adresse->setZipcode($faker->postcode);
            $adresse->setLine($faker->address);
            $adresse->setLatitude($faker->latitude);
            $adresse->setLongitude($faker->longitude);

            $manager->persist($adresse);
        }

        $manager->flush();
    }
}
