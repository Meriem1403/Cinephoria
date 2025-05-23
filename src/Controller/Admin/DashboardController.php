<?php

namespace App\Controller\Admin;

use App\Entity\Cinema;
use App\Entity\CinemaEmployee;
use App\Entity\Incident;
use App\Entity\Movie;
use App\Entity\Reservation;
use App\Entity\ReservationSeats;
use App\Entity\Review;
use App\Entity\Role;
use App\Entity\Room;
use App\Entity\Seat;
use App\Entity\Showtime;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;


#[IsGranted('ROLE_EMPLOYEE')]
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private ChartBuilderInterface $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder)
    {
        $this->chartBuilder = $chartBuilder;
    }

    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'chart' => $this->createChart(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->renderContentMaximized()
            ->setTitle('Cinéphoria');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa-solid fa-chart-line');

        yield MenuItem::section('Screenings');
        yield MenuItem::linkToCrud('Movies', 'fa-solid fa-film', Movie::class);
        yield MenuItem::linkToCrud('Showtimes', 'fa-solid fa-clock', Showtime::class);
        yield MenuItem::linkToCrud('Rooms', 'fa-solid fa-door-open', Room::class);
        yield MenuItem::linkToCrud('Seats', 'fa-solid fa-chair', Seat::class);

        yield MenuItem::section('Reservations');
        yield MenuItem::linkToCrud('Bookings', 'fa-solid fa-ticket', Reservation::class);
        yield MenuItem::linkToCrud('Booked Seats', 'fa-solid fa-th', ReservationSeats::class);

        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fa-solid fa-user', User::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Cinema Staff', 'fa-solid fa-users-gear', CinemaEmployee::class);
        yield MenuItem::linkToCrud('Roles', 'fa-solid fa-user-shield', Role::class)->setPermission('ROLE_ADMIN');

        yield MenuItem::section('Customer Feedback');
        yield MenuItem::linkToCrud('Incidents', 'fa-solid fa-triangle-exclamation', Incident::class);
        yield MenuItem::linkToCrud('Reviews', 'fa-solid fa-star', Review::class);

        yield MenuItem::section('Settings');
        yield MenuItem::linkToCrud('Cinemas', 'fa-solid fa-building', Cinema::class)->setPermission('ROLE_ADMIN');;
        yield MenuItem::linkToUrl('Homepage', 'fa-solid fa-home', $this->generateUrl('home'));
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    /**
     * @throws Exception
     */
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        if (!$user instanceof User) {
            throw new Exception('Wrong user.');
        }

        return parent::configureUserMenu($user)
            ->setAvatarUrl($user->getAvatarUrl())
            ->setMenuItems([
                MenuItem::linkToUrl('My profile', 'fa-solid fa-user', $this->generateUrl('app_settings')),
                MenuItem::linkToLogout('Logout', 'fa-solid fa-sign-out-alt'),
            ]);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('admin');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->showEntityActionsInlined()
            ->setPaginatorPageSize(8);
    }
    private function createChart(): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $chart;
    }
}
