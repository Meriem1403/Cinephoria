<?php

namespace App\DataFixtures;

use App\Entity\Seat;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeatFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Siège standard
        $seat1 = new Seat();
        $seat1->setRowLabel('A');
        $seat1->setSeatNumber(1);
        $seat1->setIsPMR(false);
        $seat1->setIsReserved(false);
        $seat1->setIsBroken(false);
        $seat1->setRoom($this->getReference('room-1', Room::class));
        $manager->persist($seat1);
        $this->addReference('seat-1', $seat1);

        // Siège PMR
        $seat2 = new Seat();
        $seat2->setRowLabel('B');
        $seat2->setSeatNumber(5);
        $seat2->setIsPMR(true);
        $seat2->setIsReserved(false);
        $seat2->setIsBroken(false);
        $seat2->setRoom($this->getReference('room-1', Room::class));
        $manager->persist($seat2);
        $this->addReference('seat-2', $seat2);

        // Siège cassé
        $seat3 = new Seat();
        $seat3->setRowLabel('C');
        $seat3->setSeatNumber(3);
        $seat3->setIsPMR(false);
        $seat3->setIsReserved(false);
        $seat3->setIsBroken(true);
        $seat3->setRoom($this->getReference('room-1', Room::class));
        $manager->persist($seat3);
        $this->addReference('seat-3', $seat3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoomFixtures::class,
        ];
    }
}
