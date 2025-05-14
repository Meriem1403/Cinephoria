<?php

namespace App\EasyAdmin;

use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

final class CustomActionField
{
    public static function new(string $propertyName = 'actions', ?string $label = null): Field
    {
        return Field::new($propertyName, $label)
            ->setTemplatePath('admin/field/custom_actions.html.twig')
            ->setFormTypeOption('mapped', false);
    }
}
