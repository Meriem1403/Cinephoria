<?php
// src/Controller/Admin/RoomCapacityApiController.php
namespace App\Controller\Admin;

use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class RoomCapacityApiController extends AbstractController
{
    #[Route('/admin/api/room/{id}/capacity', name: 'admin_room_capacity')]
    public function __invoke(Room $room): JsonResponse
    {
        return new JsonResponse([
            'capacity' => $room->getCapacity(),
            'pmr' => $room->getSeats()->filter(fn($s) => $s->getIsPMR())->count()
        ]);
    }
}

