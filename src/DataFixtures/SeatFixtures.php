<?php

namespace App\DataFixtures;

use App\Entity\Seat;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeatFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Pour chaque salle : [roomRef, nbRows, nbSeatsPerRow, rowLabels]
     * rowLabels si vide = A, B, C, ...
     */
    private const ROOMS_SEATS = [
        ['room-1', 10, 12, []],  // 120 sièges
        ['room-2', 8, 10, []],   // 80 sièges
        ['room-3', 6, 10, []],   // 60 sièges
    ];

    /** Références pour compatibilité + booking : [ref, roomRef, rowLabel, seatNum, isPMR, isBroken] */
    private const FIXED_REFS = [
        ['seat-1', 'room-1', 'A', 1, false, false],
        ['seat-2', 'room-1', 'B', 5, true, false],
        ['seat-3', 'room-1', 'C', 3, false, true],
        ['seat-4', 'room-1', 'A', 2, false, false],
        ['seat-5', 'room-1', 'A', 3, false, false],
        ['seat-6', 'room-1', 'A', 4, false, false],
        ['seat-7', 'room-1', 'B', 1, false, false],
        ['seat-8', 'room-1', 'B', 2, false, false],
        ['seat-9', 'room-1', 'D', 1, false, false],
        ['seat-10', 'room-1', 'D', 2, false, false],
        ['seat-11', 'room-1', 'E', 5, false, false],
        ['seat-12', 'room-1', 'E', 6, false, false],
    ];

    public function load(ObjectManager $manager): void
    {
        $rowLabels = range('A', 'Z');

        foreach (self::ROOMS_SEATS as [$roomRef, $nbRows, $nbSeatsPerRow, $customLabels]) {
            $room = $this->getReference($roomRef, Room::class);
            $labels = $customLabels ?: array_slice($rowLabels, 0, $nbRows);

            for ($r = 0; $r < $nbRows; $r++) {
                $rowLabel = $labels[$r];
                for ($s = 1; $s <= $nbSeatsPerRow; $s++) {
                    $isPMR = ($r === 0 && $s <= 2); // 2 premiers sièges rang A = PMR
                    $isBroken = false;
                    $ref = null;

                    foreach (self::FIXED_REFS as [$refName, $refRoom, $refRow, $refNum, $refPMR, $refBroken]) {
                        if ($refRoom === $roomRef && $refRow === $rowLabel && $refNum === $s) {
                            $isPMR = $refPMR;
                            $isBroken = $refBroken;
                            $ref = $refName;
                            break;
                        }
                    }

                    $seat = new Seat();
                    $seat->setRowLabel($rowLabel);
                    $seat->setSeatNumber($s);
                    $seat->setIsPMR($isPMR);
                    $seat->setIsReserved(false);
                    $seat->setIsBroken($isBroken);
                    $seat->setRoom($room);

                    $manager->persist($seat);
                    if ($ref !== null) {
                        $this->addReference($ref, $seat);
                    }
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoomFixtures::class,
        ];
    }
}
