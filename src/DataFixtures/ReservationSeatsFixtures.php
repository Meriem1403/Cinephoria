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
    public function load(ObjectManager $manager): void
    {
        $reservationSeat = new ReservationSeats();

        $reservationSeat->setReservation($this->getReference('reservation-1', Reservation::class));
        $reservationSeat->setSeat($this->getReference('seat-1', Seat::class));
        $reservationSeat->setPrice(9.25);
        $reservationSeat->setIsPMR(false);
        $reservationSeat->setIsValid(true);

        $manager->persist($reservationSeat);
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
