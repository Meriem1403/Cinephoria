<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\Cinema;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $room = new Room();
        $room->setName('Salle 1');
        $room->setCapacity(120);
        $room->setProjectionEquipment('4K HDR + Son Dolby Atmos');
        $room->setNotes('Salle rénovée en 2023 avec sièges confort premium.');
        $room->setCinema($this->getReference('cinema-1', Cinema::class));

        $manager->persist($room);
        $manager->flush();

        // 🔗 Pour les autres fixtures comme Showtime, Incident, etc.
        $this->addReference('room-1', $room);
    }

    public function getDependencies(): array
    {
        return [
            CinemaFixtures::class,
        ];
    }
}
