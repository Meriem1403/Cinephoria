<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            FormField::addPanel('Informations personnelles')->addCssClass('panel-section'),
            AvatarField::new('avatar')->onlyOnForms(),
            TextField::new('firstName')->onlyOnForms(),
            TextField::new('lastName')->onlyOnForms(),
            TextField::new('fullName')->onlyOnIndex(),
            DateField::new('birthDate')->onlyOnForms(),
            TextareaField::new('address')->onlyOnForms(),
            TextField::new('postalCode')->onlyOnForms(),
            TextField::new('city')->onlyOnForms(),
            CountryField::new('country')->onlyOnForms(),

            FormField::addPanel('Contact')->addCssClass('panel-section'),
            EmailField::new('email'),
            TelephoneField::new('phone'),

            FormField::addPanel('Connexion')->addCssClass('panel-section'),
            TextField::new('password')->hideOnIndex(),
            BooleanField::new('isActive')->renderAsSwitch(false),
            AssociationField::new('role')->setLabel('Role')->setHelp('Sélectionnez un rôle pour cet utilisateur'),

            FormField::addPanel('Système')->addCssClass('panel-section')->onlyOnDetail(),
            DateTimeField::new('createdAt')->onlyOnDetail(),
            DateTimeField::new('lastLogin')->onlyOnDetail(),
        ];
    }
}
