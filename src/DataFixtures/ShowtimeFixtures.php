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
    /**
     * [movieRef, roomRef, dayOffset (0=today), startTime, durationMin, lang, projection, price, label]
     * Dates are built as today + dayOffset so sessions always appear in the next days.
     */
    private const SHOWTIMES = [
        ['movie-inception', 'room-1', 0, '20:00', 148, 'VO', '4K', 9.50, 'Evening'],
        ['movie-2', 'room-1', 0, '14:30', 155, 'VO', 'IMAX', 12.90, 'IMAX'],
        ['movie-3', 'room-2', 0, '18:00', 180, 'VF', '4K', 10.50, 'VF'],
        ['movie-4', 'room-2', 1, '20:30', 152, 'VO', '4K', 9.50, 'Evening'],
        ['movie-5', 'room-1', 1, '16:00', 163, 'VF', '4K', 9.50, 'Afternoon'],
        ['movie-6', 'room-3', 1, '11:00', 105, 'VF', '2K', 7.90, 'Family'],
        ['movie-7', 'room-2', 2, '21:00', 107, 'VO', '4K', 9.50, 'Late'],
        ['movie-8', 'room-1', 2, '19:30', 141, 'VO', '4K', 10.50, 'Evening'],
        ['movie-10', 'room-3', 2, '15:00', 124, 'VF', '2K', 8.50, 'Afternoon'],
        ['movie-inception', 'room-2', 3, '20:00', 148, 'VF', '4K', 9.50, 'Evening'],
        ['movie-2', 'room-3', 3, '14:00', 155, 'VO', '4K', 11.00, 'Weekend'],
        ['movie-4', 'room-1', 4, '18:30', 152, 'VF', '4K', 9.50, 'Evening'],
        ['movie-6', 'room-2', 4, '10:30', 105, 'VF', '2K', 6.90, 'Morning'],
        ['movie-3', 'room-1', 5, '19:00', 180, 'VO', '4K', 10.50, 'Evening'],
        ['movie-5', 'room-3', 5, '17:00', 163, 'VO', '4K', 9.50, 'Afternoon'],
        ['movie-inception', 'room-3', 6, '20:00', 148, 'VO', '4K', 9.50, 'Evening'],
        ['movie-2', 'room-1', 7, '14:30', 155, 'VF', '4K', 10.50, 'Matinee'],
        ['movie-4', 'room-2', 7, '21:00', 152, 'VO', '4K', 9.50, 'Evening'],
        ['movie-6', 'room-1', 8, '11:00', 105, 'VF', '2K', 7.90, 'Family'],
        ['movie-7', 'room-3', 8, '18:00', 107, 'VO', '4K', 9.50, 'Evening'],
        ['movie-8', 'room-2', 9, '19:30', 141, 'VF', '4K', 10.50, 'Evening'],
        ['movie-10', 'room-1', 9, '15:00', 124, 'VF', '2K', 8.50, 'Afternoon'],
        ['movie-inception', 'room-1', 10, '20:00', 148, 'VO', '4K', 9.50, 'Evening'],
        ['movie-3', 'room-2', 10, '14:00', 180, 'VF', '4K', 10.50, 'Matinee'],
        ['movie-5', 'room-3', 11, '17:00', 163, 'VO', '4K', 9.50, 'Afternoon'],
        ['movie-2', 'room-2', 12, '20:00', 155, 'VO', '4K', 11.00, 'Evening'],
        ['movie-4', 'room-1', 13, '18:30', 152, 'VF', '4K', 9.50, 'Evening'],
        ['movie-6', 'room-3', 14, '10:30', 105, 'VF', '2K', 6.90, 'Morning'],
        // Séances "aujourd'hui" pour plus de films (liste triée par date de sortie = premiers clics)
        ['movie-9', 'room-1', 0, '17:00', 138, 'VO', '4K', 9.50, 'Afternoon'],
        ['movie-11', 'room-2', 0, '19:30', 88, 'VF', '2K', 7.90, 'Evening'],
        ['movie-12', 'room-3', 0, '14:00', 162, 'VO', '4K', 9.50, 'Matinee'],
        ['movie-13', 'room-1', 0, '21:00', 115, 'VF', '4K', 9.50, 'Late'],
        ['movie-14', 'room-2', 0, '16:30', 155, 'VO', '4K', 10.50, 'Afternoon'],
        ['movie-15', 'room-3', 0, '11:00', 128, 'VF', '2K', 8.50, 'Morning'],
        ['movie-16', 'room-1', 0, '20:00', 122, 'VO', '4K', 9.50, 'Evening'],
        ['movie-17', 'room-2', 0, '18:00', 106, 'VF', '4K', 9.50, 'Evening'],
        ['movie-18', 'room-3', 0, '15:00', 165, 'VO', '4K', 10.50, 'Afternoon'],
        ['movie-19', 'room-1', 0, '13:00', 96, 'VO', '4K', 9.50, 'Matinee'],
        ['movie-20', 'room-2', 0, '20:30', 127, 'VF', '4K', 9.50, 'Evening'],
        ['movie-21', 'room-3', 0, '12:00', 118, 'VO', '4K', 9.50, 'Noon'],
        ['movie-22', 'room-1', 0, '19:00', 143, 'VF', '4K', 9.50, 'Evening'],
        ['movie-23', 'room-2', 0, '17:30', 110, 'VO', '4K', 9.50, 'Afternoon'],
        ['movie-24', 'room-3', 0, '14:30', 164, 'VO', '4K', 10.50, 'Matinee'],
        ['movie-25', 'room-1', 0, '21:15', 149, 'VF', '4K', 9.50, 'Late'],
    ];

    public function load(ObjectManager $manager): void
    {
        $today = (new DateTimeImmutable())->setTime(0, 0, 0);

        foreach (self::SHOWTIMES as $i => $data) {
            [$movieRef, $roomRef, $dayOffset, $startTime, $duration, $lang, $projection, $price, $label] = $data;

            $room = $this->getReference($roomRef, Room::class);
            $date = $today->modify("+{$dayOffset} days");
            $start = new DateTimeImmutable($date->format('Y-m-d') . ' ' . $startTime);
            $end = $start->modify("+{$duration} minutes");

            $showtime = new Showtime();
            $showtime->setMovie($this->getReference($movieRef, Movie::class));
            $showtime->setRoom($room);
            $showtime->setDate($start);
            $showtime->setStartTime($start);
            $showtime->setEndTime($end);
            $showtime->setLanguage([$lang]);
            $showtime->setChosenLanguage($lang);
            $showtime->setProjectionType($projection);
            $showtime->setStatus('scheduled');
            $showtime->setAvailableSeats($room->getCapacity());
            $showtime->setPmrSeats(4);
            $showtime->setPrice($price);
            // Enable special pricing for ~1/3 of showtimes (e.g. index 0, 3, 6, 9, ...)
            $useSpecialPricing = ($i % 3) === 0;
            $showtime->setSpecialPrice($useSpecialPricing);
            $showtime->setSpecialPrices($useSpecialPricing ? [
                ['label' => 'Student', 'price' => round($price * 0.7, 2), 'note' => 'With valid student ID'],
                ['label' => 'Child', 'price' => round($price * 0.5, 2), 'note' => 'Under 12 years'],
                ['label' => 'Senior', 'price' => round($price * 0.8, 2), 'note' => '65+ years'],
            ] : []);
            $showtime->setLabel($label);
            $showtime->setNotes('Air-conditioned room.');

            $manager->persist($showtime);
            $this->addReference('showtime-' . ($i + 1), $showtime);
        }

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
