<?php

namespace App\Controller\Api;

use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class SeatApiController extends AbstractController
{
    #[Route('/api/seats/by-room/{id}', name: 'api_seats_by_room', methods: ['GET'])]
    public function byRoom(Room $room): JsonResponse
    {
        $seats = $room->getSeats();

        $data = [];
        foreach ($seats as $seat) {
            $data[] = [
                'id' => $seat->getId(),
                'label' => $seat->getLabel(),
                'rowLabel' => $seat->getRowLabel(),
                'seatNumber' => $seat->getSeatNumber(),
                'isReserved' => $seat->getIsReserved(),
                'isBroken' => $seat->getIsBroken(),
                'isPMR' => $seat->getIsPMR(),
                'currentlyReserved' => $seat->isCurrentlyReserved(),
                'status' => $seat->getStatus(),
            ];
        }

        return new JsonResponse($data);
    }
}
