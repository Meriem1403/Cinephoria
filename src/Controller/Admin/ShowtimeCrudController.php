<?php

namespace App\Controller\Admin;

use App\Entity\Showtime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class ShowtimeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Showtime::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('startTime', 'Début de la séance'),
            DateTimeField::new('endTime', 'Fin de la séance'),
            AssociationField::new('movie', 'Film')->autocomplete(),
            AssociationField::new('room', 'Salle')->autocomplete(),
        ];
    }
}
