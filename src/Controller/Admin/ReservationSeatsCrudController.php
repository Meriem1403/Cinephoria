<?php
// src/Controller/Admin/ReservationSeatsCrudController.php
namespace App\Controller\Admin;

use App\Entity\ReservationSeats;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, AssociationField, MoneyField, BooleanField};

class ReservationSeatsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReservationSeats::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Reserved Seat')
            ->setEntityLabelInPlural('Reserved Seats')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('reservation')->setLabel('Reservation'),
            AssociationField::new('seat')->setLabel('Seat')
                ->formatValue(fn($v,$e)=>$e->getSeat()?->getLabel() ?? ''),
            MoneyField::new('price')->setCurrency('EUR'),
            BooleanField::new('isPMR')->setLabel('PMR'),
            BooleanField::new('isValid')->setLabel('Valid'),
        ];
    }
}
