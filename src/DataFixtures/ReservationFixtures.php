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
    public function load(ObjectManager $manager): void
    {
        $reservation = new Reservation();

        $reservation->setUser($this->getReference('employee-user-1', User::class));
        $reservation->setShowtime($this->getReference('showtime-1', Showtime::class));
        $reservation->setReservationDate(new DateTime('2024-04-28 19:00'));
        $reservation->setStatus('confirmée');
        $reservation->setTotalPrice(18.50);

        $manager->persist($reservation);
        $this->addReference('reservation-1', $reservation);

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
