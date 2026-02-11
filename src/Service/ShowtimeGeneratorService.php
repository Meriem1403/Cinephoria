<?php

namespace App\Service;

use App\Entity\Movie;
use App\Entity\Room;
use App\Entity\Showtime;
use App\Repository\MovieRepository;
use App\Repository\RoomRepository;
use App\Repository\ShowtimeRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Génère automatiquement des séances pour les films à l'affiche.
 * Chaque film a au moins une séance par jour. Modifiable dans le backoffice EasyAdmin.
 */
class ShowtimeGeneratorService
{
    /** Créneaux pour couvrir tous les films (nb slots × nb salles >= nb films) */
    private const DEFAULT_TIME_SLOTS = [
        '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00',
        '18:00', '19:00', '20:00', '21:00', '22:00', '23:00',
    ];

    private const DEFAULT_PRICE = 9.50;
    private const DEFAULT_PROJECTION = '4K';
    private const DEFAULT_PMR_SEATS = 4;

    public function __construct(
        private readonly MovieRepository $movieRepository,
        private readonly RoomRepository $roomRepository,
        private readonly ShowtimeRepository $showtimeRepository,
        private readonly EntityManagerInterface $em,
    ) {
    }

    /**
     * Génère des séances pour les prochains $daysAhead jours pour tous les films à l'affiche.
     * Ne crée pas de doublon (même film, salle, date, heure).
     *
     * @return int Nombre de séances créées
     */
    public function generate(int $daysAhead = 14): int
    {
        $movies = $this->movieRepository->findBy(['atCinema' => true], ['id' => 'ASC']);
        $rooms = $this->roomRepository->findBy([], ['id' => 'ASC']);

        if ($movies === [] || $rooms === []) {
            return 0;
        }

        $today = (new \DateTimeImmutable())->setTime(0, 0, 0);
        $created = 0;
        $slots = self::DEFAULT_TIME_SLOTS;
        $nbCombos = \count($slots) * \count($rooms); // (créneau, salle) = 1 séance max

        for ($d = 0; $d < $daysAhead; $d++) {
            $date = $today->modify("+{$d} days");

            // Chaque film à l'affiche a au moins une séance ce jour-là
            foreach ($movies as $movieIndex => $movie) {
                $comboIndex = $movieIndex % $nbCombos;
                $slotIndex = (int) ($comboIndex / \count($rooms));
                $roomIndex = $comboIndex % \count($rooms);
                $room = $rooms[$roomIndex];
                $timeSlot = $slots[$slotIndex];

                $startTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $timeSlot);
                if (!$startTime) {
                    continue;
                }

                if ($this->showtimeRepository->existsForSlot($movie, $room, $date, $startTime)) {
                    continue;
                }

                $showtime = $this->createShowtime($movie, $room, $date, $startTime, true);
                $this->em->persist($showtime);
                $created++;
            }
        }

        $this->em->flush();

        return $created;
    }

    private function createShowtime(Movie $movie, Room $room, \DateTimeImmutable $date, \DateTimeImmutable $startTime, bool $withSpecialPricing = false): Showtime
    {
        $duration = $movie->getDuration() ?? 120;
        $endTime = $startTime->modify("+{$duration} minutes");

        $languages = $movie->getLanguage();
        $chosenLanguage = $languages !== [] ? (string) $languages[0] : 'VF';

        $price = self::DEFAULT_PRICE;

        $showtime = new Showtime();
        $showtime->setMovie($movie);
        $showtime->setRoom($room);
        $showtime->setDate($startTime);
        $showtime->setStartTime($startTime);
        $showtime->setEndTime($endTime);
        $showtime->setLanguage([$chosenLanguage]);
        $showtime->setChosenLanguage($chosenLanguage);
        $showtime->setProjectionType(self::DEFAULT_PROJECTION);
        $showtime->setStatus('scheduled');
        $showtime->setAvailableSeats($room->getCapacity());
        $showtime->setPmrSeats(self::DEFAULT_PMR_SEATS);
        $showtime->setPrice($price);
        $showtime->setSpecialPrice($withSpecialPricing);
        $showtime->setSpecialPrices($withSpecialPricing ? [
            ['label' => 'Student', 'price' => round($price * 0.7, 2), 'note' => 'With valid student ID'],
            ['label' => 'Child', 'price' => round($price * 0.5, 2), 'note' => 'Under 12 years'],
            ['label' => 'Senior', 'price' => round($price * 0.8, 2), 'note' => '65+ years'],
        ] : []);
        $showtime->setLabel('Auto');
        $showtime->setNotes(null);

        return $showtime;
    }
}
