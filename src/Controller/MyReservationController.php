<?php
// src/Controller/MyReservationController.php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyReservationController extends AbstractController
{
#[Route('/my-reservations', name: 'my_reservations')]
public function index(ReservationRepository $reservationRepository): Response
{
$user = $this->getUser();
$reservations = $reservationRepository->findBy(['user' => $user], ['reservationDate' => 'DESC']);

return $this->render('admin/reservation/my_reservations.html.twig', [
'reservations' => $reservations,
]);
}
}
