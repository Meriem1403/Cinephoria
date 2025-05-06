<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('user', 'Review written by')->hideOnForm()
            ->autocomplete()
                ->formatValue(static function ($value, Review $review) {
                    $user = $review->getUser();
                    if (!$user) {
                        return null;
                    }

                    return sprintf('%s (%s)', $user->getEmail(), $user->getReviews()->count());

            }),
            NumberField::new('rating', 'Total Rating')
            ->setTextAlign('right'),
            AssociationField::new('movie', 'Movie'),
            TextareaField::new('comment'),
            DateTimeField::new('createdAt')->hideOnForm(),
            BooleanField::new('isApproved'),


        ];
    }
    public function __construct(private readonly Security $security)  {}

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Review && $entityInstance->getUser() === null) {
            $entityInstance->setUser($this->security->getUser());
            $entityInstance->setIsApproved(false);
        }
        parent::persistEntity($entityManager, $entityInstance);
    }
}
