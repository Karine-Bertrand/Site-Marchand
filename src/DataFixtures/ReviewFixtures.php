<?php

namespace App\DataFixtures;

use App\Entity\Review;
use App\Repository\CompanyRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository, ReviewRepository $reviewRepository, CompanyRepository $companyRepository)
    {
        $this->userRepository = $userRepository;
        $this->reviewRepository = $reviewRepository;
        $this->companyRepository = $companyRepository;
    }

    public function load(ObjectManager $manager)
    {

        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        // on créé 10 adresses
        for ($i = 0; $i < 100; $i++) {
            $review = new Review();

            $review->setRating(rand(0,10));
            $review->setComment($faker->text);
            $review->setRating(rand(0,10));
            $review->setUser($this->userRepository->find(rand(1,500)));
            $review->setCompany($this->companyRepository->find(rand(1,100)));

            

            $manager->persist($review);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CompanyFixtures::class,
        ];
    }
}
