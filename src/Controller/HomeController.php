<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MovieRepository;
use Symfony\Component\Finder\Finder;

final class HomeController extends AbstractController
{
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
            ->getQuery()
            ->getResult();

        $dramaMovies = array_filter($moviesAtCinema, function ($movie) {
            return in_array('drama', $movie->getGenre(), true);
        });

        $fantasyMovies = array_filter($moviesAtCinema, function ($movie) {
            return in_array('fantasy', $movie->getGenre(), true);
        });


        return $this->render('home/index.html.twig', [
            'heroImages' => $heroImages,
            'moviesAtCinema' => $moviesAtCinema,
            'dramaMovies' => $dramaMovies,
            'fantasyMovies' => $fantasyMovies,
        ]);
    }
}