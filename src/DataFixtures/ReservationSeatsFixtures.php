<?php

namespace App\DataFixtures;

use App\Entity\ReservationSeats;
use App\Entity\Reservation;
use App\Entity\Seat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationSeatsFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * [reservationRef, [seatRef1, seatRef2, ...], isPMR pour chaque siège]
     * Un siège ne peut être réservé qu'une fois par séance (réparti entre résas différentes).
     */
    private const RESERVATION_SEATS = [
        ['reservation-1', ['seat-1', 'seat-4', 'seat-5'], [false, false, false]],
        ['reservation-2', ['seat-6', 'seat-7', 'seat-8'], [false, false, false]],
        ['reservation-3', ['seat-9', 'seat-10'], [false, false]],
        ['reservation-4', ['seat-11', 'seat-12'], [false, false]],
        ['reservation-5', ['seat-2', 'seat-4'], [true, false]],   // PMR
        ['reservation-6', ['seat-5', 'seat-6'], [false, false]],
        ['reservation-7', ['seat-7', 'seat-8'], [false, false]],
        ['reservation-8', ['seat-8', 'seat-9'], [false, false]],
        ['reservation-9', ['seat-10', 'seat-11'], [false, false]], // annulée
    ];

    public function load(ObjectManager $manager): void
    {
        $refIndex = 0;
        foreach (self::RESERVATION_SEATS as [$reservationRef, $seatRefs, $isPmrFlags]) {
            $reservation = $this->getReference($reservationRef, Reservation::class);
            $totalPrice = $reservation->getTotalPrice();
            $count = \count($seatRefs);
            $pricePerSeat = $count > 0 ? round($totalPrice / $count, 2) : 0;

            foreach ($seatRefs as $i => $seatRef) {
                $seat = $this->getReference($seatRef, Seat::class);
                $rs = new ReservationSeats();
                $rs->setReservation($reservation);
                $rs->setSeat($seat);
                $rs->setPrice($pricePerSeat);
                $rs->setIsPMR($isPmrFlags[$i] ?? false);
                $rs->setIsValid($reservation->getStatus() === 'confirmée');

                $manager->persist($rs);
                $this->addReference('reservation-seat-' . (++$refIndex), $rs);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ReservationFixtures::class,
            SeatFixtures::class,
        ];
    }
}
