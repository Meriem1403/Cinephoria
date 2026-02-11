<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Showtime;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * [userRef, showtimeRef, date, status, totalPrice]
     * Uniquement séances en room-1 (showtime-1, 2, 5, 8, 12) pour pouvoir attribuer des sièges.
     */
    private const RESERVATIONS = [
        ['employee-user-1', 'showtime-1', '2025-03-15 19:45', 'confirmée', 28.50],
        ['user-client-1', 'showtime-2', '2025-03-16 14:00', 'confirmée', 38.70],
        ['user-client-2', 'showtime-5', '2025-03-15 15:30', 'confirmée', 19.00],
        ['user-client-1', 'showtime-1', '2025-03-15 19:50', 'confirmée', 19.00],
        ['user-client-2', 'showtime-8', '2025-03-19 19:00', 'confirmée', 21.00],
        ['user-client-1', 'showtime-12', '2025-03-23 18:00', 'confirmée', 19.00],
        ['employee-user-1', 'showtime-2', '2025-03-16 13:50', 'en_attente', 25.80],
        ['user-client-2', 'showtime-1', '2025-03-15 19:40', 'confirmée', 19.00],
        ['user-client-1', 'showtime-5', '2025-03-15 15:20', 'annulée', 0],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::RESERVATIONS as $i => $data) {
            [$userRef, $showtimeRef, $dateStr, $status, $totalPrice] = $data;

            $reservation = new Reservation();
            $reservation->setUser($this->getReference($userRef, User::class));
            $reservation->setShowtime($this->getReference($showtimeRef, Showtime::class));
            $reservation->setReservationDate(new DateTime($dateStr));
            $reservation->setStatus($status);
            $reservation->setTotalPrice($totalPrice);

            $manager->persist($reservation);
            $this->addReference('reservation-' . ($i + 1), $reservation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ShowtimeFixtures::class,
        ];
    }
}
