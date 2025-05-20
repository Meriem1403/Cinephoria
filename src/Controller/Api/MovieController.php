<?php
// src/Controller/Api/MovieController.php
namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/movies', name: 'api_movies_')]
class MovieController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(MovieRepository $movieRepo): JsonResponse
    {
        $movies = $movieRepo->findAll();
        $data = array_map(fn(Movie $m) => [
            'id'           => $m->getId(),
            'title'        => $m->getTitle(),
            'description'  => $m->getDescription(),
            'duration'     => $m->getDuration(),
            'releaseDate'  => $m->getReleaseDate()?->format('Y-m-d'),
            'ageRating'    => $m->getAgeRating(),
            'posterUrl'    => $m->getPosterUrl(),
            'isFavorite'   => $m->getIsFavorite(),
            'rating'       => $m->getRating(),
            'atCinema'     => $m->getAtCinema(),
            'genre'        => $m->getGenre(),
            'language'     => $m->getLanguage(),
            'heroImage'    => $m->getHeroImage(),
        ], $movies);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(MovieRepository $movieRepo, int $id): JsonResponse
    {
        $m = $movieRepo->find($id);
        if (!$m) {
            return $this->json(['error' => 'Movie non trouvé'], 404);
        }
        return $this->json([
            'id'           => $m->getId(),
            'title'        => $m->getTitle(),
            'description'  => $m->getDescription(),
            'duration'     => $m->getDuration(),
            'releaseDate'  => $m->getReleaseDate()?->format('Y-m-d'),
            'ageRating'    => $m->getAgeRating(),
            'posterUrl'    => $m->getPosterUrl(),
            'isFavorite'   => $m->getIsFavorite(),
            'rating'       => $m->getRating(),
            'atCinema'     => $m->getAtCinema(),
            'genre'        => $m->getGenre(),
            'language'     => $m->getLanguage(),
            'heroImage'    => $m->getHeroImage(),
        ]);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH','PUT'])]
    public function update(
        int $id,
        Request $request,
        MovieRepository $movieRepo,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $movie = $movieRepo->find($id);
        if (!$movie) {
            return $this->json(['error' => 'Movie non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (array_key_exists('isFavorite', $data)) {
            $movie->setIsFavorite((bool)$data['isFavorite']);
        }
        // Gérer d'autres champs si nécessaire

        $em = $doctrine->getManager();
        $em->persist($movie);
        $em->flush();

        return $this->json([
            'success'    => true,
            'isFavorite' => $movie->getIsFavorite(),
        ]);
    }
}