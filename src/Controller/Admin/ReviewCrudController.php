<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->setDisabled()
                ->hideWhenCreating(),
            AssociationField::new('user', 'Review written by')->hideOnForm()
                ->autocomplete()
                ->formatValue(static function ($value, Review $review) {
                    $user = $review->getUser();
                    if (!$user) {
                        return null;
                    }

                    return sprintf('%s (%s)', $user->getEmail(), $user->getReviews()->count());

                }),
            ChoiceField::new('rating', ' Votes')
                ->setChoices([
                    '<i class="fas fa-thumbs-down text-danger"></i>' => 1,
                    '<i class="fas fa-thumbs-up text-warning"></i>' => 2,
                    '<i class="fas fa-thumbs-up text-success"></i><i class="fas fa-thumbs-up text-success ml-1"></i>' => 3,
                ])
                ->renderExpanded()
                ->setTemplatePath('admin/field/movie_rating.html.twig')
                ->setFormTypeOption('disabled',
                    $pageName == Crud::PAGE_EDIT),

            AssociationField::new('movie', 'Movie')
                ->renderAsNativeWidget()
                ->setFormTypeOption('disabled',
                    $pageName == Crud::PAGE_EDIT),


            TextareaField::new('comment')
                ->setFormTypeOption('disabled',
                    $pageName == Crud::PAGE_EDIT)
                ->hideOnIndex(),

            DateTimeField::new('createdAt')->hideOnForm(),

            BooleanField::new('isApproved'),


        ];
    }

    public function __construct(private readonly Security $security)
    {
    }

    private function updateMovieRating(EntityManagerInterface $entityManager, Review $review): void
    {
        $movie = $review->getMovie();
        if (!$movie) return;

        $reviews = $movie->getReviews();
        $count = $reviews->count();

        if ($count === 0) {
            $movie->setRating(null);
        } else {
            $total = array_sum(array_map(fn($r) => $r->getRating(), $reviews->toArray()));
            $average = round($total / $count, 1);
            $movie->setRating($average);
        }

        $entityManager->persist($movie);
        $entityManager->flush();
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);

        if ($entityInstance instanceof Review) {
            $this->updateMovieRating($entityManager, $entityInstance);
        }
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Review) {
            return;
        }

        if ($entityInstance->getUser() === null) {
            $entityInstance->setUser($this->security->getUser());
            $entityInstance->setIsApproved(false);
        }


        parent::persistEntity($entityManager, $entityInstance);


        $this->updateMovieRating($entityManager, $entityInstance);
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action
                    ->setLabel('')
                    ->setIcon('fa fa-pen')
                    ->addCssClass('ea-button-edit');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action
                    ->setLabel('')
                    ->setIcon('fa fa-eye')
                    ->addCssClass('ea-button-detail');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action
                    ->setLabel('')
                    ->setIcon('fa fa-trash')
                    ->addCssClass('ea-button-delete');
            });
    }
    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->overrideTemplate('crud/index', 'admin/review/review_index.html.twig');

    }

}
