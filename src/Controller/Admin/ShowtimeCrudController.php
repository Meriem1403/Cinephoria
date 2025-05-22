<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Entity\Showtime;
use App\Form\SpecialPriceType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{ArrayField,
    AssociationField,
    BooleanField,
    CollectionField,
    DateField,
    Field,
    IdField,
    IntegerField,
    MoneyField,
    TimeField};
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ShowtimeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Showtime::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setFormOptions([
            'attr' => ['data-controller' => 'showtime-form']
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        return [
            IdField::new('id')->hideOnForm(),

            // Movie : non modifiable pour l'employé
            AssociationField::new('movie')
                ->setFormTypeOption('choice_label', 'title')
                ->setFormTypeOption('attr', [
                    'data-showtime-form-target' => 'movieSelect',
                    'data-action' => 'change->showtime-form#render'
                ])
                ->setRequired(true)
                ->setColumns(6)
                ->setFormTypeOption('disabled', !$isAdmin),

            // Room : non modifiable pour l'employé
            AssociationField::new('room')
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('attr', [
                    'data-showtime-form-target' => 'roomSelect',
                    'data-action' => 'change->showtime-form#loadRoomData'
                ])
                ->setRequired(true)
                ->setColumns(6)
                ->setFormTypeOption('disabled', !$isAdmin),

            DateField::new('date')
                ->setColumns(4)
                ->setFormTypeOption('disabled', !$isAdmin), // Par exemple

            TimeField::new('startTime')
                ->setFormTypeOption('attr', [
                    'data-showtime-form-target' => 'startTime',
                    'data-action' => 'change->showtime-form#handleStartTimeChange'
                ])
                ->setColumns(4)
                ->setFormTypeOption('disabled', !$isAdmin),

            TimeField::new('endTime')
                ->setFormTypeOption('disabled', true)
                ->setFormTypeOption('attr', ['data-showtime-form-target' => 'endTime'])
                ->setHelp('calculated from start time and movie duration')
                ->setColumns(4),

            Field::new('chosenLanguage')
                ->setFormType(ChoiceType::class)
                ->setFormTypeOptions([
                    'choices' => [
                        'VF' => 'VF',
                        'VO' => 'VO',
                        'VOSTFR' => 'VOSTFR',
                    ],
                    'attr' => [
                        'data-showtime-form-target' => 'language',
                        'class' => 'language-select',
                    ],
                    'disabled' => !$isAdmin, // Non modifiable pour employé
                ])
                ->setHelp('Language loaded from the selected movie')
                ->setColumns(6),

            IntegerField::new('availableSeats')
                ->setLabel('Room capacity')
                ->setFormTypeOption('disabled', true)
                ->setFormTypeOption('attr', ['data-showtime-form-target' => 'capacity'])
                ->setHelp('loaded from the selected room')
                ->setColumns(3),

            IntegerField::new('pmrSeats')
                ->setLabel('PMR seats')
                ->setFormTypeOption('disabled', true)
                ->setFormTypeOption('attr', ['data-showtime-form-target' => 'pmr'])
                ->setHelp('Number of PMR seats in the room')
                ->setColumns(3),

            MoneyField::new('price')
                ->setCurrency('EUR')
                ->setNumDecimals(2)
                ->setColumns(4)
                ->setFormTypeOption('disabled', !$isAdmin),

            BooleanField::new('specialPrice')
                ->setLabel('Enable special pricing')
                ->setFormTypeOption('disabled', !$isAdmin),

            CollectionField::new('specialPrices')
                ->setEntryType(SpecialPriceType::class)
                ->setLabel('Special prices')
                ->onlyOnForms()
                ->allowAdd()
                ->allowDelete()
                ->setColumns(12)
                ->setHelp('Each price includes a label, a value and an optional note')
                ->setFormTypeOption('disabled', !$isAdmin),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Showtime) {
            return;
        }

        $movie = $entityInstance->getMovie();
        $room = $entityInstance->getRoom();

        // Calcul automatique de l’heure de fin
        if ($movie && $entityInstance->getStartTime()) {
            $end = (clone $entityInstance->getStartTime())
                ->modify('+' . $movie->getDuration() . ' minutes');
            $entityInstance->setEndTime($end);
        }

        // Si la langue n'est pas sélectionnée, on met la première langue du film
        if ($movie && !$entityInstance->getChosenLanguage()) {
            $languages = $movie->getLanguage();
            if (!empty($languages)) {
                $entityInstance->setChosenLanguage($languages[0]); // met la première langue
            }
        }

        // Valeur par défaut pour le statut
        if (!$entityInstance->getStatus()) {
            $entityInstance->setStatus('scheduled');
        }

        // Si une salle est sélectionnée, on remplit capacity et PMR
        if ($room) {
            $entityInstance->setAvailableSeats($room->getCapacity());

            $pmrCount = 0;
            foreach ($room->getSeats() as $seat) {
                if ($seat->getIsPMR()) {
                    $pmrCount++;
                }
            }
            $entityInstance->setPmrSeats($pmrCount);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}