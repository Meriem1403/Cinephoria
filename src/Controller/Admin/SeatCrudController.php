<?php

namespace App\Controller\Admin;


use App\Entity\Seat;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SeatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Seat::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('label', 'Code')->onlyOnIndex(),
            IdField::new('id')->onlyOnDetail(),
            TextField::new('rowLabel', 'Row')
                ->setFormTypeOption('disabled',
                $pageName == Crud::PAGE_EDIT)
                ->hideOnIndex(),
            IntegerField::new('seatNumber', 'Seat Number')
                ->setFormTypeOption('disabled',
                $pageName == Crud::PAGE_EDIT)
                ->hideOnIndex(),
            AssociationField::new('room', 'Room')->setFormTypeOption('disabled',
                $pageName == Crud::PAGE_EDIT),
            BooleanField::new('isPMR', 'PMR'),
            BooleanField::new('isReserved', 'Reserved'),
            BooleanField::new('isBroken', 'Defective'),

            ];
    }


}