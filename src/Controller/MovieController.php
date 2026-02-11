<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\CinemaRepository;
use App\Repository\ShowtimeRepository;
use App\Repository\ReviewRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MovieRepository;
use Symfony\Component\Finder\Finder;

final class MovieController extends AbstractController
{
    /** Genre slugs (URL) and English labels (keys = DB values) */
    private const GENRE_FILTERS = [
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

    private const MOVIES_PER_PAGE = 12;

    #[Route('/movies', name: 'movies')]
    public function list(MovieRepository $movieRepository, Request $request): Response
    {
        $genre = mb_strtolower((string) $request->query->get('genre', ''));
        $page = max(1, (int) $request->query->get('page', 1));

        $moviesAtCinema = $movieRepository->createQueryBuilder('m')
            ->where('m.atCinema = :val')
            ->setParameter('val', true)
            ->orderBy('m.releaseDate', 'DESC')
            ->getQuery()
            ->getResult();

        if ($genre !== '') {
            $moviesAtCinema = array_values(array_filter($moviesAtCinema, function ($movie) use ($genre) {
                return \in_array($genre, array_map('mb_strtolower', $movie->getGenre()), true);
            }));
        }

        $totalCount = \count($moviesAtCinema);
        $totalPages = max(1, (int) ceil($totalCount / self::MOVIES_PER_PAGE));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * self::MOVIES_PER_PAGE;
        $movies = array_slice($moviesAtCinema, $offset, self::MOVIES_PER_PAGE);

        $genresForFilter = [];
        foreach (self::GENRE_FILTERS as $dbKey => $label) {
            $genresForFilter[] = ['slug' => mb_strtolower($dbKey), 'label' => $label];
        }

        $heroImages = [];
        $dirPath = __DIR__ . '/../../public/pictures/hero';
        if (is_dir($dirPath)) {
            $finder = new Finder();
            foreach ($finder->files()->in($dirPath) as $file) {
                $heroImages[] = '/pictures/hero/' . $file->getRelativePathname();
            }
        }

        $selectedGenreLabel = null;
        if ($genre !== '') {
            foreach (self::GENRE_FILTERS as $dbKey => $label) {
                if (mb_strtolower($dbKey) === $genre) {
                    $selectedGenreLabel = $label;
                    break;
                }
            }
        }

        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'selectedGenre' => $genre,
            'selectedGenreLabel' => $selectedGenreLabel,
            'genres' => $genresForFilter,
            'heroImages' => $heroImages,
        ]);
    }

    #[Route('/movies/{id}', name: 'movie_show', requirements: ['id' => '\d+'])]
    public function show(
        #[MapEntity(mapping: ['id' => 'id'])]
        Movie $movie,
        CinemaRepository $cinemaRepository,
        ShowtimeRepository $showtimeRepository,
        ReviewRepository $reviewRepository,
        Request $request
    ): Response {
        $cinemas = $cinemaRepository->findAll();
        $showtimes = $showtimeRepository->findBy(['movie' => $movie]);

        $reviews = $reviewRepository->findBy([
            'movie' => $movie,
            'isApproved' => true
        ]);

        $referer = $request->headers->get('Referer');
        $backUrl = $referer && str_contains($referer, $request->getSchemeAndHttpHost())
            ? $referer
            : $this->generateUrl('movies');

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'cinemas' => $cinemas,
            'showtimes' => $showtimes,
            'reviews' => $reviews,
            'backUrl' => $backUrl,
        ]);
    }
}
