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

        // à supprimer
        /*
        $dummyMovies = [
            ['title' => 'The alto knights', 'pictures' => 'pictures/films/ak1.jpg'],
            ['title' => 'Aznavour', 'pictures' => 'pictures/films/az1.jpg'],
            ['title' => 'Le compte de Montecristo', 'pictures' => 'pictures/films/cm1.jpg'],
            ['title' => 'Dune', 'pictures' => 'pictures/films/du1.jpg'],
            ['title' => 'Joker', 'pictures' => 'pictures/films/jo1.jpg'],
            ['title' => 'La haine', 'pictures' => 'pictures/films/lh1.jpg'],
            ['title' => 'Locked', 'pictures' => 'pictures/films/lo1.jpg'],
            ['title' => 'Minecraft le film', 'pictures' => 'pictures/films/mi1.jpg'],
            ['title' => 'Narnia', 'pictures' => 'pictures/films/mn1.jpg'],
            ['title' => 'Retour vers le futur', 'pictures' => 'pictures/films/oe1.jpg'],
        ];

        return $this->render('home/index.html.twig', [
            'heroImages' => $heroImages,
            'moviesAtCinema' => $dummyMovies,
            'dramaMovies' => $dummyMovies,
            'fantasyMovies' => $dummyMovies,
        ]);
        */

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