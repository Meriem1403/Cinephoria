<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\CinemaRepository;
use App\Repository\ShowtimeRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MovieRepository;
use Symfony\Component\Finder\Finder;

final class MovieController extends AbstractController
{
    #[Route('/movies', name: 'movies')]
    public function list(MovieRepository $movieRepository, Request $request): Response
    {
        $genre = strtolower($request->query->get('genre', ''));

        // Récupérer les films en salle
        $moviesAtCinema = $movieRepository->createQueryBuilder('m')
            ->where('m.atCinema = :val')
            ->setParameter('val', true)
            ->getQuery()
            ->getResult();

        // Filtrer par genre
        if (!empty($genre)) {
            $moviesAtCinema = array_filter($moviesAtCinema, function ($movie) use ($genre) {
                return in_array($genre, array_map('strtolower', $movie->getGenre()), true);
            });
        }

        // Charger les images pour le footer
        $heroImages = [];
        $dirPath = __DIR__ . '/../../public/pictures/hero';

        if (is_dir($dirPath)) {
            $finder = new Finder();
            foreach ($finder->files()->in($dirPath) as $file) {
                $heroImages[] = '/pictures/hero/' . $file->getRelativePathname();
            }
        }

        return $this->render('movie/list.html.twig', [
            'movies' => $moviesAtCinema,
            'selectedGenre' => $genre,
            'heroImages' => $heroImages,
        ]);
    }

    #[Route('/movies/{id}', name: 'movie_show')]
    public function show(
        Movie $movie,
        CinemaRepository $cinemaRepository,
        ShowtimeRepository $showtimeRepository,
        ReviewRepository $reviewRepository
    ): Response {
        $cinemas = $cinemaRepository->findAll();
        $showtimes = $showtimeRepository->findBy(['movie' => $movie]);

        // ✅ On récupère uniquement les avis approuvés
        $reviews = $reviewRepository->findBy([
            'movie' => $movie,
            'isApproved' => true
        ]);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'cinemas' => $cinemas,
            'showtimes' => $showtimes,
            'reviews' => $reviews,
        ]);
    }
}
