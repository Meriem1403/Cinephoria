<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $currentUser = $this->getUser();

        return [
            IdField::new('id')->hideOnForm(),

            FormField::addPanel('Personal information')->addCssClass('panel-section'),

            AvatarField::new('avatar')
                ->formatValue(static function ($value, User $user) {
                    return $user->getAvatarUrl();
                })
                ->hideOnForm(),

            ImageField::new('avatar')
                ->setBasePath('/pictures/uploads/')
                ->setUploadDir('public/pictures/uploads/')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setHelp('Choose a picture for your profile')
                ->onlyOnForms(),

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
            BooleanField::new('isActive')->renderAsSwitch(false)
                ->setFormTypeOption('disabled', !$isAdmin),

            // ➡️ Le champ "roles" non modifiable sauf pour admin
            ChoiceField::new('roles')
                ->setLabel('Roles')
                ->allowMultipleChoices()
                ->setChoices([
                    'Admin'    => 'ROLE_ADMIN',
                    'Employee' => 'ROLE_EMPLOYEE',
                    'User'     => 'ROLE_USER',
                ])
                ->renderExpanded(false)
                ->setHelp('Sélectionne un ou plusieurs rôles pour cet utilisateur.')
                ->setFormTypeOption('disabled', !$isAdmin),

            FormField::addPanel('Système')->addCssClass('panel-section')->onlyOnDetail(),
            DateTimeField::new('createdAt')->onlyOnDetail(),
            DateTimeField::new('lastLogin')->onlyOnDetail(),
        ];
    }
}
