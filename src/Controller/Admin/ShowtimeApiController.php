<?php

namespace App\Controller\Admin;

use App\Repository\ShowtimeRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class ShowtimeApiController extends AbstractController
{
    #[Route('/admin/api/showtime/check-conflict', name: 'admin_showtime_check_conflict', methods: ['GET'])]
    public function checkConflict(Request $request, ShowtimeRepository $repo): JsonResponse
    {
        $roomId = $request->query->get('roomId');
        $date = $request->query->get('date');
        $start = $request->query->get('start');
        $end = $request->query->get('end');

        if (!$roomId || !$date || !$start || !$end) {
            return new JsonResponse([
                'conflict' => false,
                'error' => 'Missing parameters',
            ], 400);
        }

        try {
            // Formatage précis des heures de début et de fin
            $startTime = new DateTime("$date $start");
            $endTime = new DateTime("$date $end");
            $dateObj = new DateTime($date);

            // Recherche des séances qui se chevauchent
            $conflicts = $repo->createQueryBuilder('s')
                ->andWhere('s.room = :roomId')
                ->andWhere('s.date = :date')
                ->andWhere('s.startTime < :endTime')
                ->andWhere('s.endTime > :startTime')
                ->setParameter('roomId', $roomId)
                ->setParameter('date', $dateObj)
                ->setParameter('startTime', $startTime)
                ->setParameter('endTime', $endTime)
                ->getQuery()
                ->getResult();

            return new JsonResponse([
                'conflict' => count($conflicts) > 0,
            ]);
        } catch (Throwable $e) {
            return new JsonResponse([
                'conflict' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
