<?php
// src/Controller/Admin/MovieLanguageApiController.php
namespace App\Controller\Admin;

use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class MovieLanguageApiController extends AbstractController
{
    #[Route('/admin/api/movie/{id}/languages', name: 'admin_movie_languages', requirements: ['id' => '\d+'])]
    public function __invoke(
        #[MapEntity(mapping: ['id' => 'id'])]
        Movie $movie
    ): JsonResponse {
        return new JsonResponse([
            'languages' => $movie->getLanguage(),
            'duration' => $movie->getDuration(), // en minutes
        ]);
    }
}

