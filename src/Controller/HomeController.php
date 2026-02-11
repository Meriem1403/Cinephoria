<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MovieRepository;
use Symfony\Component\Finder\Finder;

final class HomeController extends AbstractController
{
    /** Section labels by genre (keys = DB genre values) */
    private const GENRE_SECTIONS = [
        'Drame' => 'Drama',
        'Comédie' => 'Comedy',
        'Science-fiction' => 'Science Fiction',
        'Action' => 'Action',
        'Animation' => 'Animation',
        'Aventure' => 'Adventure',
        'Thriller' => 'Thriller',
        'Historique' => 'Historical',
        'Familial' => 'Family',
    ];

    #[Route('/', name: 'home')]
    public function index(MovieRepository $movieRepository): Response
    {
        $finder = new Finder();
        $dirPath = __DIR__ . '/../../public/pictures/hero';
        $heroImages = [];
        if (is_dir($dirPath)) {
            foreach ($finder->files()->in($dirPath) as $file) {
                $heroImages[] = '/pictures/hero/' . $file->getRelativePathname();
            }
        }

        $moviesAtCinema = $movieRepository->createQueryBuilder('m')
            ->where('m.atCinema = :val')
            ->setParameter('val', true)
            ->orderBy('m.releaseDate', 'DESC')
            ->getQuery()
            ->getResult();

        $sections = [];

        /** Minimum for "Now showing" section */
        $minForAtCinema = 6;
        /** Minimum for genre sections (demo: 10 films per carousel) */
        $minForGenreSections = 10;

        // 1. "Now showing" section (all movies at cinema)
        if (\count($moviesAtCinema) >= $minForAtCinema) {
            $sections[] = [
                'title' => 'Now showing',
                'movies' => $moviesAtCinema,
                'withGradientTop' => true,
                'withGradientBottom' => false,
            ];
        }

        // 2. One section per genre with at least 10 films
        foreach (self::GENRE_SECTIONS as $genreKey => $genreLabel) {
            $byGenre = array_filter($moviesAtCinema, function ($movie) use ($genreKey) {
                return in_array($genreKey, $movie->getGenre(), true);
            });
            if (\count($byGenre) >= $minForGenreSections) {
                $sections[] = [
                    'title' => $genreLabel,
                    'movies' => array_values($byGenre),
                    'withGradientTop' => false,
                    'withGradientBottom' => false,
                ];
            }
        }

        // Last section gets bottom gradient when multiple sections
        if (\count($sections) > 1) {
            $sections[\count($sections) - 1]['withGradientBottom'] = true;
        }

        return $this->render('home/index.html.twig', [
            'heroImages' => $heroImages,
            'sections' => $sections,
        ]);
    }
}