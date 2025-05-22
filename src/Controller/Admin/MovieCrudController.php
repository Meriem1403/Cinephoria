<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYEE')]
class MovieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $fields = [];

        $fields[] = IdField::new('id')->hideOnForm();

        // Titre du film : modifiable uniquement pour admin
        $fields[] = TextField::new('title', 'Title')
            ->setFormTypeOption('disabled', !$isAdmin);

        // Poster : modifiable uniquement pour admin
        $fields[] = ImageField::new('posterUrl', 'Poster')
            ->setUploadDir('public/pictures/films/')
            ->setBasePath('pictures/films/')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->setRequired($pageName === 'new')
            ->setFormTypeOption('disabled', !$isAdmin);

        $fields[] = ImageField::new('heroImage', 'Hero Image')
            ->setUploadDir('public/pictures/hero/')
            ->setBasePath('pictures/hero/')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->hideOnIndex()
            ->setFormTypeOption('disabled', !$isAdmin);

        $fields[] = ChoiceField::new('genre', 'Genres')
            ->allowMultipleChoices()
            ->setChoices([
                'Action' => 'action',
                'Drama' => 'drama',
                'Fantasy' => 'fantasy',
                'Comedy' => 'comedy',
                'Thriller' => 'thriller',
                'Animation' => 'animation',
                'Horror' => 'horror',
            ])
            ->renderExpanded()
            ->setFormTypeOption('disabled', !$isAdmin);

        $fields[] = ArrayField::new('language', 'Languages')
            ->setFormTypeOption('disabled', !$isAdmin);

        $fields[] = DateField::new('releaseDate', 'Release Date')
            ->hideOnIndex()
            ->setFormTypeOption('disabled', !$isAdmin);

        $fields[] = NumberField::new('duration', 'Duration (minutes)')
            ->hideOnIndex()
            ->setFormTypeOption('disabled', !$isAdmin);

        $fields[] = NumberField::new('ageRating', 'Age Rating')
            ->hideOnIndex()
            ->setFormTypeOption('disabled', !$isAdmin);

        $fields[] = Field::new('rating', 'Total votes')
            ->onlyOnIndex()
            ->formatValue(function ($value) {
                return $value / 3;
            })
            ->setTextAlign('right')
            ->setTemplatePath('/admin/field/movie_rating.html.twig')
            ->onlyOnIndex();

        $fields[] = TextEditorField::new('description', 'Synopsis')
            ->setFormTypeOption('disabled', !$isAdmin);

        $fields[] = BooleanField::new('atCinema', 'Currently in Cinemas')
            ->renderAsSwitch(false)
            ->setFormTypeOption('disabled', !$isAdmin);

        $fields[] = BooleanField::new('isFavorite', 'Marked as Favorite')
            ->setFormTypeOption('disabled', !$isAdmin);

        $fields[] = DateTimeField::new('createdAt', 'Created At')->onlyOnDetail();

        return $fields;
    }
}