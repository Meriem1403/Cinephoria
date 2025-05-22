<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    AssociationField,
    ChoiceField,
    DateTimeField,
    IdField,
    MoneyField,
    TextField
};
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYEE')]
class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Reservation')
            ->setEntityLabelInPlural('Reservations')
            ->setDefaultSort(['reservationDate' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        return [
            IdField::new('id')->onlyOnIndex(),

            AssociationField::new('user')
                ->formatValue(fn ($value, $entity) => $entity->getUser()?->getFullName())
                ->setLabel('User')
                ->setFormTypeOption('disabled', !$isAdmin),

            // Showtimes non éditable par l'employé
            AssociationField::new('showtime')
                ->setLabel('Showtime')
                ->setFormTypeOption('disabled', !$isAdmin)
                ->hideOnIndex(),

            TextField::new('showtimeInfo', 'Showtime')
                ->onlyOnIndex()
                ->formatValue(function ($value, $entity) {
                    $showtime = $entity->getShowtime();
                    if (!$showtime) return '';
                    $movie = $showtime->getMovie()?->getTitle() ?? '—';
                    $room = $showtime->getRoom()?->getName() ?? '';
                    $start = $showtime->getStartTime()?->format('H:i') ?? '';
                    $end = $showtime->getEndTime()?->format('H:i') ?? '';
                    return "$movie – $room [$start - $end]";
                }),

            TextField::new('seatCodes', 'Seats')
                ->onlyOnIndex()
                ->formatValue(function ($value, $entity) {
                    return implode(', ', array_map(
                        fn($rs) => $rs->getSeat()?->getLabel(),
                        $entity->getReservationSeats()->toArray()
                    ));
                }),

            DateTimeField::new('reservationDate')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->hideOnForm(),

            MoneyField::new('totalPrice')
                ->setCurrency('EUR')
                ->setNumDecimals(2)
                ->hideOnForm(),

            ChoiceField::new('status')
                ->setChoices([
                    'Pending' => 'pending',
                    'Confirmed' => 'confirmed',
                    'Cancelled' => 'cancelled',
                ])
                ->renderAsBadges([
                    'pending' => 'warning',
                    'confirmed' => 'success',
                    'cancelled' => 'danger',
                ])
                ->setFormTypeOption('disabled', !$isAdmin), // seul l'admin peut changer le status
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Reservation) {
            return;
        }

        // Marquer les sièges comme réservés
        foreach ($entityInstance->getReservationSeats() as $rs) {
            $seat = $rs->getSeat();
            if ($seat) {
                $seat->setIsReserved(true);
                $entityManager->persist($seat);
            }
        }

        // Calcul automatique du prix total à partir des sièges
        $total = 0;
        foreach ($entityInstance->getReservationSeats() as $rs) {
            $total += $rs->getPrice();
        }
        $entityInstance->setTotalPrice($total);

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Reservation) {
            return;
        }

        // Si annulation -> libérer les sièges
        if ($entityInstance->getStatus() === 'cancelled') {
            foreach ($entityInstance->getReservationSeats() as $rs) {
                $seat = $rs->getSeat();
                if ($seat) {
                    $seat->setIsReserved(false);
                    $entityManager->persist($seat);
                }
            }
        }

        // Recalcul automatique du prix total
        $total = 0;
        foreach ($entityInstance->getReservationSeats() as $rs) {
            $total += $rs->getPrice();
        }
        $entityInstance->setTotalPrice($total);

        parent::updateEntity($entityManager, $entityInstance);
    }
}
