<?php

namespace App\Controller\Admin;

use App\Entity\Cinema;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField,
    CountryField,
    EmailField,
    IdField,
    NumberField,
    TelephoneField,
    TextareaField,
    TextField};
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class CinemaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cinema::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Cinema')
            ->setEntityLabelInPlural('Cinemas')
            ->setDefaultSort(['city' => 'ASC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('city'))
            ->add(TextFilter::new('name'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnDetail(),

            TextField::new('name'),
            TextField::new('city'),
            TextareaField::new('address'),
            NumberField::new('postalCode')->setLabel('Postal Code'),
            CountryField::new('country'),

            TelephoneField::new('phone'),
            EmailField::new('email'),

            AssociationField::new('rooms')->hideOnForm()->setLabel('Rooms'),
            AssociationField::new('employees')->hideOnForm()->setLabel('Staff'),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        // Si l'employé n'est PAS admin, il n'a pas l'action "edit"
        if ($this->isGranted('ROLE_EMPLOYEE') && !$this->isGranted('ROLE_ADMIN')) {
            return $actions
                ->disable(Action::EDIT);
        }
        return $actions;
    }
}
