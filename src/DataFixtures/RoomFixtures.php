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
        $cinema = $this->getReference('cinema-1', Cinema::class);

        $roomsData = [
            ['Salle 1', 120, ['4K HDR', 'Son Dolby Atmos'], 'Salle principale rénovée en 2023, sièges confort premium.'],
            ['Salle 2', 80, ['4K', 'Son 5.1'], 'Salle moyenne, idéale pour les films d\'art et essai.'],
            ['Salle 3', 60, ['2K', 'Son stéréo'], 'Petite salle intimiste.'],
        ];

        foreach ($roomsData as $i => $data) {
            [$name, $capacity, $equipment, $notes] = $data;
            $room = new Room();
            $room->setName($name);
            $room->setCapacity($capacity);
            $room->setProjectionEquipment($equipment);
            $room->setNotes($notes);
            $room->setCinema($cinema);

            $manager->persist($room);
            $this->addReference('room-' . ($i + 1), $room);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CinemaFixtures::class,
        ];
    }
}
