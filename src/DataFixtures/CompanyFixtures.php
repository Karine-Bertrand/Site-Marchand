<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Repository\DriveRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository, CompanyRepository $companyRepository, DriveRepository $driveRepository)
    {
        $this->driveRepository = $driveRepository;
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
    }

    public function load(ObjectManager $manager)
    {

        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        // on créé 10 adresses
        for ($i = 1; $i <= 100; $i++) {
            $company = new Company();

            $company->setName($faker->company);
            $company->setSiret($faker->siret);
            $company->setDescription($faker->realText);
            $company->setValidated(true);
            $company->setUser($this->userRepository->find($i));
            for ($j = 1; $j<=5; $j++){
                $company->addDrive($this->driveRepository->find($j));
            }
            

            $manager->persist($company);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DriveFixtures::class,
            UserFixtures::class,
        ];
    }
}
