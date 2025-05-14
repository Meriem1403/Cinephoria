<?php

namespace App\Controller;

use App\Repository\ShowtimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowtimeController extends AbstractController
{
    #[Route('//showtimes/{id}', name: 'showtime_show')]
    public function index(ShowtimeRepository $showtimeRepository): Response
    {
        // Récupère les séances triées par date et heure
        $showtimes = $showtimeRepository->createQueryBuilder('s')
            ->join('s.movie', 'm')->addSelect('m')
            ->join('s.room', 'r')->addSelect('r')
            ->join('r.cinema', 'c')->addSelect('c')
            ->orderBy('s.date', 'ASC')
            ->addOrderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getResult();

        // Tri facultatif ou regroupement si nécessaire

        return $this->render('movie/show.html.twig', [
            'showtimes' => $showtimes,
        ]);
    }
}
