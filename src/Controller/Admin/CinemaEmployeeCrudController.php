<?php

namespace App\Controller\Admin;

use App\Entity\CinemaEmployee;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    AssociationField,
    BooleanField,
    DateField,
    IdField,
    TextField
};
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class CinemaEmployeeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CinemaEmployee::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Cinema Employee')
            ->setEntityLabelInPlural('Cinema Team')
            ->setDefaultSort(['assignedSince' => 'DESC'])
            ->overrideTemplate('crud/index', 'admin/employee/cinema_employee.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnDetail(),

            AssociationField::new('user')
                ->setRequired(true)
                ->setLabel('User')
                ->setFormTypeOption('choice_label', 'fullName')
                ->setFormTypeOption('disabled', true),

            AssociationField::new('cinema')
                ->setRequired(true)
                ->setFormTypeOption('disabled', true),

            TextField::new('jobTitle')->setFormTypeOption('disabled', true),

            DateField::new('assignedSince')->setFormTypeOption('disabled', true),

            BooleanField::new('isActive')->renderAsSwitch()->setFormTypeOption('disabled', true),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('cinema')->setLabel('Cinema'));
    }
}
