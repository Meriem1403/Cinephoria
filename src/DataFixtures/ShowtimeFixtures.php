<?php

namespace App\DataFixtures;

use App\Entity\Showtime;
use App\Entity\Movie;
use App\Entity\Room;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ShowtimeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $showtime = new Showtime();

        $showtime->setMovie($this->getReference('movie-inception', Movie::class));
        $showtime->setRoom($this->getReference('room-1', Room::class));

        $showtime->setDate(new DateTimeImmutable('2024-05-05'));
        $showtime->setStartTime(new DateTimeImmutable('2024-05-05 20:00'));
        $showtime->setEndTime(new DateTimeImmutable('2024-05-05 22:30'));
        $showtime->setLanguage('FR');
        $showtime->setProjectionType('4K');
        $showtime->setStatus('disponible');
        $showtime->setAvailableSeats(120);
        $showtime->setPmrSeats(4);
        $showtime->setPrice(9.50);
        $showtime->setSpecialPrice(false);
        $showtime->setLabel('Avant-première');
        $showtime->setNotes('Salle climatisée, sous-titres disponibles.');

        $manager->persist($showtime);
        $this->addReference('showtime-1', $showtime);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MovieFixtures::class,
            RoomFixtures::class,
        ];
    }
}
