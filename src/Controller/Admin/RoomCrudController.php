<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RoomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Room::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $projectionOptions = ['4K', 'IMAX', 'Dolby Sound'];

        return [
            TextField::new('name', 'Room Name'),
            AssociationField::new('cinema', 'Cinema'),
            NumberField::new('capacity', 'Capacity'),
            TextEditorField::new('notes', 'Notes'),

            ChoiceField::new('projectionEquipment', 'Projection Equipment')
                ->setChoices(array_combine($projectionOptions, $projectionOptions))
                ->allowMultipleChoices()
                ->renderExpanded()
                ->renderAsBadges(),
        ];
    }

}
