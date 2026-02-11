<?php
// src/Controller/ReviewController.php
namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
#[Route('/review/new/{movieId}', name: 'review_new')]
public function new(
int $movieId,
Request $request,
EntityManagerInterface $em
): Response {
$movie = $em->getRepository(Movie::class)->find($movieId);

if (!$movie) {
throw $this->createNotFoundException('Movie not found');
}

$review = new Review();
$review->setCreatedAt(new \DateTimeImmutable());
$review->setIsApproved(false); // ✅ modération
$review->setMovie($movie);
$review->setUser($this->getUser());

$form = $this->createForm(ReviewType::class, $review);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$em->persist($review);
$em->flush();

$this->addFlash('success', 'Your review has been submitted and is awaiting moderation.');
return $this->redirectToRoute('app_booking', ['id' => $movie->getId()]);
}

return $this->render('admin/review/new.html.twig', [
'form' => $form,
'movie' => $movie,
]);
}
}
