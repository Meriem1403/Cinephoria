<?php

namespace App\Controller\Admin;

use App\Entity\Cinema;
use App\Entity\CinemaEmployee;
use App\Entity\Incident;
use App\Entity\Movie;
use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\Role;
use App\Entity\Room;
use App\Entity\Seat;
use App\Entity\Showtime;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cinephoria');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa-solid fa-home');
        yield MenuItem::linkToCrud('Incident', 'fa-solid fa-triangle-exclamation', Incident::class);
        yield MenuItem::linkToCrud('Showtime', 'fa-solid fa-clock', Showtime::class);
        yield MenuItem::linkToCrud('Reservation', 'fa-solid fa-ticket', Reservation::class);
        yield MenuItem::linkToCrud('Employee', 'fa-solid fa-users-gear', CinemaEmployee::class);
        yield MenuItem::linkToCrud('Seat map', 'fa-solid fa-chair', Seat::class);
        yield MenuItem::linkToCrud('Current Movie', 'fa-solid fa-film', Movie::class);
        yield MenuItem::linkToCrud('Analytics', 'fa-solid fa-chart-line', Room::class);
        yield MenuItem::linkToCrud('User', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Review', 'fa-solid fa-star', Review::class);
        yield MenuItem::linkToCrud('Local team', 'fa-solid fa-user-shield', Role::class);
        yield MenuItem::linkToCrud('Cinema', 'fa-solid fa-building', Cinema::class);
        yield MenuItem::linkToCrud('Movie', 'fa-solid fa-clapperboard', Movie::class);
        yield MenuItem::linkToCrud('Room', 'fa-solid fa-door-open', Room::class);
    }
}
