<?php

namespace App\DataFixtures;

use App\Entity\Incident;
use App\Entity\User;
use App\Entity\Showtime;
use App\Entity\Room;
use App\Entity\Seat;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class IncidentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $incident = new Incident();

        $incident->setUser($this->getReference('admin-user', User::class));
        $incident->setShowtime($this->getReference('showtime-1', Showtime::class));
        $incident->setRoom($this->getReference('room-1', Room::class));
        $incident->setSeat($this->getReference('seat-1', Seat::class));

        $incident->setTitle('Projecteur en panne');
        $incident->setDescription('Le projecteur principal ne fonctionne plus depuis 10 minutes.');
        $incident->setStatus('en_attente');
        $incident->setCreatedAt(new DateTimeImmutable());
        $incident->setResolvedAt(null);

        $manager->persist($incident);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ShowtimeFixtures::class,
            RoomFixtures::class,
            SeatFixtures::class,
        ];
    }
}
