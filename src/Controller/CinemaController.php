<?php
// src/Controller/CinemaController.php
namespace App\Controller;

use App\Repository\CinemaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CinemaController extends AbstractController
{
    #[Route('/cinemas', name: 'cinemas')]
    public function index(CinemaRepository $cinemaRepository): Response
    {
        $cinemas = $cinemaRepository->findAll();

        // Charger les images comme pour la homepage
        $heroImages = [];
        $dirPath = __DIR__ . '/../../public/pictures/hero';

        if (is_dir($dirPath)) {
            $finder = new Finder();
            foreach ($finder->files()->in($dirPath) as $file) {
                $heroImages[] = '/pictures/hero/' . $file->getRelativePathname();
            }
        }

        return $this->render('cinema/cinema.html.twig', [
            'cinemas' => $cinemas,
            'heroImages' => $heroImages,
        ]);
    }
}
