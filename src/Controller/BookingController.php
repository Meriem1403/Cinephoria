<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\ReservationSeats;
use App\Entity\Seat;
use App\Entity\Showtime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController
{
    /**
     * Affiche la seatmap et stocke en session la sélection de sièges.
     */
    #[Route('/booking/{id}', name: 'app_booking', methods: ['GET','POST'])]
    public function bookShowtime(
        Showtime $showtime,
        Request  $request
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($request->isMethod('POST')) {
            // Récupère tout le POST, puis la clé 'seats' ou tableau vide
            $params = $request->request->all();
            $rawSeats = $params['seats'] ?? [];
            $selectedSeatIds = is_array($rawSeats) ? $rawSeats : [];

            $request->getSession()->set('selectedSeats', $selectedSeatIds);

            return $this->redirectToRoute('booking_formulas', [
                'id' => $showtime->getId(),
            ]);
        }

        $allSeats    = $showtime->getRoom()->getSeats();
        $reservedIds = [];
        foreach ($showtime->getReservations() as $res) {
            foreach ($res->getReservationSeats() as $rs) {
                $reservedIds[] = $rs->getSeat()->getId();
            }
        }

        return $this->render('admin/reservation/book.html.twig', [
            'showtime'         => $showtime,
            'seats'            => $allSeats,
            'reservedSeatsIds' => $reservedIds,
        ]);
    }

    /**
     * Affiche la page de sélection des formules et persiste la réservation.
     */
    #[Route('/booking/formulas/{id}', name: 'booking_formulas', methods: ['GET','POST'])]
    public function formulas(
        Showtime               $showtime,
        Request                $request,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $session         = $request->getSession();
        $selectedSeatIds = $session->get('selectedSeats', []);
        $countSeats      = count($selectedSeatIds);

        if ($countSeats === 0) {
            $this->addFlash('warning', 'Please select at least one seat first.');
            return $this->redirectToRoute('app_booking', ['id' => $showtime->getId()]);
        }

        $specialDefs = $showtime->getSpecialPrices() ?? [];
        $useSpecial  = $showtime->getSpecialPrice() && !empty($specialDefs);

        if ($request->isMethod('POST')) {
            $params = $request->request->all();

            if ($useSpecial) {
                $rawCounts     = $params['specialCount'] ?? [];
                $specialCounts = is_array($rawCounts) ? array_map(fn($v)=>(int)$v, $rawCounts) : [];
                $sumSpecials   = array_sum($specialCounts);
                $standardCount = $countSeats - $sumSpecials;
            } else {
                $standardCount = isset($params['standardCount']) ? (int)$params['standardCount'] : 0;
                $specialCounts = [];
                $sumSpecials   = 0;
            }

            if (($sumSpecials + $standardCount) !== $countSeats) {
                $this->addFlash('error', 'The total number of tickets must equal the number of selected seats.');
                return $this->redirectToRoute('booking_formulas', ['id' => $showtime->getId()]);
            }

            $reservation = (new Reservation())
                ->setUser($this->getUser())
                ->setShowtime($showtime)
                ->setStatus('pending')
                ->setReservationDate(new DateTimeImmutable());
            $em->persist($reservation);

            $totalPrice = 0.0;
            $seatRepo   = $em->getRepository(Seat::class);
            $idsQueue   = $selectedSeatIds;

            if ($useSpecial) {
                foreach ($specialDefs as $idx => $def) {
                    $count = $specialCounts[$idx] ?? 0;
                    $price = (float) $def['price'];
                    for ($i = 0; $i < $count; $i++) {
                        $seatId = array_shift($idsQueue);
                        if (!$seat = $seatRepo->find($seatId)) {
                            continue;
                        }
                        $rs = (new ReservationSeats())
                            ->setReservation($reservation)
                            ->setSeat($seat)
                            ->setPrice($price)
                            ->setIsValid(true)
                            ->setIsPMR($seat->getIsPMR());
                        $em->persist($rs);
                        $totalPrice += $price;
                    }
                }
            }

            for ($i = 0; $i < $standardCount; $i++) {
                $seatId = array_shift($idsQueue);
                if (!$seat = $seatRepo->find($seatId)) {
                    continue;
                }
                $priceStd = (float) $showtime->getPrice();
                $rs = (new ReservationSeats())
                    ->setReservation($reservation)
                    ->setSeat($seat)
                    ->setPrice($priceStd)
                    ->setIsValid(true)
                    ->setIsPMR($seat->getIsPMR());
                $em->persist($rs);
                $totalPrice += $priceStd;
            }

            $reservation->setTotalPrice($totalPrice);
            $em->flush();

            $session->remove('selectedSeats');
            $this->addFlash('success', 'Reservation successful!');

            return $this->redirectToRoute('booking_confirmation', [
                'reservationId' => $reservation->getId(),
            ]);
        }

        return $this->render('admin/reservation/book_formulas.html.twig', [
            'showtime'    => $showtime,
            'countSeats'  => $countSeats,
            'useSpecial'  => $useSpecial,
            'specialDefs' => $specialDefs,
        ]);
    }

    #[Route('/booking/confirmation/{reservationId}', name: 'booking_confirmation', methods: ['GET'])]
    public function confirmation(int $reservationId, EntityManagerInterface $em): Response
    {
        $reservation = $em->getRepository(Reservation::class)->find($reservationId);
        if (!$reservation) {
            throw $this->createNotFoundException('Reservation not found');
        }

        return $this->render('admin/reservation/confirmation.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * Génère et télécharge l’e-ticket en PDF (wkhtmltopdf via Snappy).
     */
    #[Route(
        '/booking/confirmation/{reservationId}/download',
        name: 'booking_confirmation_download',
        methods: ['GET']
    )]
    public function downloadEticket(
        int $reservationId,
        EntityManagerInterface $em,
        Pdf $pdf
    ): Response {
        $reservation = $em->getRepository(Reservation::class)->find($reservationId);
        if (!$reservation) {
            throw $this->createNotFoundException('Reservation not found');
        }

        // Encodage base64 de la bannière et du logo
        $publicDir   = $this->getParameter('kernel.project_dir').'/public';
        $heroPath    = $publicDir.'/pictures/hero/'.$reservation->getShowtime()->getMovie()->getHeroImage();
        $logoPath    = $publicDir.'/pictures/logo.png';

        $bannerDataUri = 'data:image/'.pathinfo($heroPath, PATHINFO_EXTENSION).';base64,'
            .base64_encode(file_get_contents($heroPath));
        $logoDataUri   = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));

        // Génération du HTML
        $html = $this->renderView('admin/reservation/confirmation_pdf.html.twig', [
            'reservation'   => $reservation,
            'bannerDataUri' => $bannerDataUri,
            'logoDataUri'   => $logoDataUri,
        ]);

        // Options pour wkhtmltopdf (Snappy)
        $options = [
            'enable-local-file-access' => true,
        ];

        // Génération du PDF
        $pdfContent = $pdf->getOutputFromHtml($html, $options);

        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="e-ticket_%d.pdf"', $reservationId),
            ]
        );
    }
}
