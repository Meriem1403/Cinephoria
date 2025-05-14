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
                ->setFormTypeOption('choice_label', 'fullName'),

            AssociationField::new('cinema')
                ->setRequired(true),

            TextField::new('jobTitle'),

            DateField::new('assignedSince'),

            BooleanField::new('isActive')->renderAsSwitch(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('cinema')->setLabel('Cinema'));
    }

}
