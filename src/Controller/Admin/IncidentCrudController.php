<?php

namespace App\Controller\Admin;

use App\Entity\Incident;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYEE')]
class IncidentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Incident::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            ChoiceField::new('category')
                ->setChoices([
                    'Projector' => 'projector',
                    'Sound' => 'sound',
                    'Seating' => 'seating',
                    'Lighting' => 'lighting',
                    'Other' => 'other',
                ]),
            ChoiceField::new('status')
                ->setChoices([
                    'Open' => 'open',
                    'In progress' => 'in_progress',
                    'Resolved' => 'resolved',
                    'Closed' => 'closed',
                ])
                ->renderAsBadges([
                    'open' => 'warning',      // Jaune
                    'in_progress' => 'info',  // Bleu
                    'resolved' => 'success',  // Vert
                    'closed' => 'danger',     // Rouge
                ]),
            TextareaField::new('description'),
            AssociationField::new('room')
                ->setLabel('Room')
                ->autocomplete(),
            AssociationField::new('showtime')
                ->setLabel('Showtime')
                ->autocomplete(),
            // PAS de champ user dans le form, il est géré automatiquement
            DateTimeField::new('createdAt')
                ->setLabel('Created At')
                ->onlyOnIndex(),
            DateTimeField::new('updatedAt')
                ->setLabel('Updated At')
                ->onlyOnDetail(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Incident) {
            return;
        }
        // Assigne automatiquement l'utilisateur connecté comme "user"
        $user = $this->getUser();
        if ($user) {
            $entityInstance->setUser($user);
        }
        parent::persistEntity($entityManager, $entityInstance);
    }
}
