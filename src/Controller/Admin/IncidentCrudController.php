<?php

namespace App\Controller\Admin;

use App\Entity\Incident;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

class IncidentCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Incident::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Incident subject'),

            TextField::new('title', 'Incident Title'),
            DateTimeField::new('createdAt')->hideOnForm(),
            TextField::new('category', 'Category'),
            AssociationField::new('showtime', 'Séance')->autocomplete(),
            AssociationField::new('room', 'Room / Location')->autocomplete(),
            TextareaField::new('description', 'Description'),
            TextField::new('status', 'Status'),
            AssociationField::new('reportedBy', 'Reported by')->autocomplete(),
            AssociationField::new('user')->hideOnForm(),

            ];
    }

    public function createEntity(string $entityFqcn): Incident
    {
        $incident = new Incident();

        $user = $this->security->getUser();
        if ($user instanceof User) {
            $incident->setUser($user);
        }

        return $incident;
    }
}
