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
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimezoneField;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;

class MovieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];

        $fields[] = IdField::new('id')->hideOnForm();

        $fields[] = TextField::new('title', 'Title');

        $fields[] = ImageField::new('posterUrl', 'Poster')
            ->setUploadDir('public/pictures/films/')
            ->setBasePath('pictures/films/')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->setRequired($pageName === 'new');

        $fields[] = ImageField::new('heroImage', 'Hero Image')
            ->setUploadDir('public/pictures/hero/')
            ->setBasePath('pictures/hero/')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->hideOnIndex();

        $fields[] = ChoiceField::new('genre', 'Genres')
            ->allowMultipleChoices()
            ->setChoices(['Action' => 'action',
                'Drama' => 'drama',
                'Fantasy' => 'fantasy',
                'Comedy' => 'comedy',
                'Thriller' => 'thriller',
                'Animation' => 'animation',
                'Horror' => 'horror',
                ])
            ->renderExpanded();

        $fields[] = ArrayField::new('language', 'Languages');

        $fields[] = DateField::new('releaseDate', 'Release Date')->hideOnIndex();
        $fields[] = NumberField::new('duration', 'Duration (minutes)')->hideOnIndex();
        $fields[] = NumberField::new('ageRating', 'Age Rating')->hideOnIndex();

        $fields[] = TextEditorField::new('description', 'Description')->hideOnIndex();

        $fields[] = BooleanField::new('atCinema', 'Currently in Cinemas');
        $fields[] = BooleanField::new('isFavorite', 'Marked as Favorite');

        $fields[] = DateTimeField::new('createdAt', 'Created At')->onlyOnDetail();

        return $fields;
    }

}
