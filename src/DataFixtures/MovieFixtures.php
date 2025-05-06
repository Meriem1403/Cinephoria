<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle('Inception');
        $movie->setDescription('Un voleur expérimenté dans l\'art de l\'extraction des rêves est engagé pour implanter une idée dans l\'esprit d\'un dirigeant.');
        $movie->setDuration(148);
        $movie->setReleaseDate(new DateTime('2010-07-16'));
        $movie->setLanguage(['VO']);
        $movie->setAgeRating('16');
        $movie->setPosterUrl('fo1.jpg');
        $movie->setIsFavorite(true);
        $movie->setRating(8.8);
        $movie->setCreatedAt(new DateTimeImmutable());
        $movie->setAtCinema(false);
        $movie->setHeroImage('co.jpg');

        $manager->persist($movie);
        $this->addReference('movie-inception', $movie);

        $manager->flush();
    }
}
