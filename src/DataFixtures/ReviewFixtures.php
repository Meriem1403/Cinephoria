<?php

namespace App\DataFixtures;

use App\Entity\Review;
use App\Entity\User;
use App\Entity\Movie;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $review = new Review();

        $review->setUser($this->getReference('employee-user-1', User::class));
        $review->setMovie($this->getReference('movie-inception', Movie::class));
        $review->setRating(4.5);
        $review->setComment('Très bon film, scénario original et visuellement impressionnant.');
        $review->setCreatedAt(new DateTimeImmutable());
        $review->setIsApproved(true);

        $manager->persist($review);

        $this->addReference('review-1', $review);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            MovieFixtures::class,
        ];
    }
}
