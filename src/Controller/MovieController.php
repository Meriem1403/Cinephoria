<?php

namespace App\Controller;

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

        // Filtrer par genre si un genre est demandé
        if (!empty($genre)) {
            $moviesAtCinema = array_filter($moviesAtCinema, function ($movie) use ($genre) {
                return in_array($genre, array_map('strtolower', $movie->getGenre()), true);
            });
        }

        // Charger les images pour le footer (carousel)
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
            'heroImages' => $heroImages, // ✅ pour le footer
        ]);
    }
}
