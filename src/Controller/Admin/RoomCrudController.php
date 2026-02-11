<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Entity\Seat;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RoomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Room::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Room')
            ->setEntityLabelInPlural('Rooms')
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->setFormTypeOption('disabled', !$isAdmin),
            AssociationField::new('cinema')->setFormTypeOption('disabled', !$isAdmin),
            NumberField::new('capacity')->setFormTypeOption('disabled', !$isAdmin),
            TextEditorField::new('notes')->setFormTypeOption('disabled', !$isAdmin),
            ChoiceField::new('projectionEquipment')
                ->setChoices(['4K' => '4K', 'IMAX' => 'IMAX', 'Dolby Sound' => 'Dolby Sound'])
                ->allowMultipleChoices()
                ->renderExpanded()
                ->renderAsBadges()
                ->setFormTypeOption('disabled', !$isAdmin),
            AssociationField::new('seats')->onlyOnDetail(),
        ];
    }



    public function configureActions(Actions $actions): Actions
    {
        $seatMap = Action::new('seatMap', 'Seat Map')
            ->linkToCrudAction('seatMap')
            ->setIcon('fa fa-chair')
            ->setCssClass('btn btn-primary');

        // Employé : uniquement Seat Map (ni edit, ni delete, ni new)
        if ($this->isGranted('ROLE_ADMIN')) {
            return $actions
                ->add(Crud::PAGE_DETAIL, $seatMap)
                ->add(Crud::PAGE_INDEX, $seatMap);
            // admin aura toutes les actions par défaut
        }

        // Employé : supprime tout sauf Seat Map
        return $actions
            ->disable(Action::EDIT, Action::DELETE, Action::NEW)
            ->add(Crud::PAGE_DETAIL, $seatMap)
            ->add(Crud::PAGE_INDEX, $seatMap);
    }

    public function seatMap(Request $request, EntityManagerInterface $em): Response
    {
        $roomId = $request->query->get('entityId');

        if (!$roomId) {
            throw new LogicException('No room ID provided.');
        }

        $room = $em->getRepository(Room::class)->find($roomId);

        if (!$room) {
            throw $this->createNotFoundException('Room not found.');
        }

        return $this->render('admin/reservation/seatmap.html.twig', [
            'room' => $room,
        ]);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Room) return;

        parent::persistEntity($entityManager, $entityInstance);

        $capacity = $entityInstance->getCapacity();
        $columns = 11;
        $rows = ceil($capacity / $columns);
        $seatCounter = 0;

        for ($r = 0; $r < $rows; $r++) {
            $rowLetter = chr(65 + $r);
            for ($c = 1; $c <= $columns; $c++) {
                $seatCounter++;
                if ($seatCounter > $capacity) break;

                $seat = new Seat();
                $seat->setRoom($entityInstance);
                $seat->setRowLabel($rowLetter);
                $seat->setSeatNumber($c);
                $seat->setIsPMR(false);
                $seat->setIsBroken(false);
                $seat->setIsReserved(false);
                $entityManager->persist($seat);
            }
        }

        $entityManager->flush();
    }
}
