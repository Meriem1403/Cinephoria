<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    /**
     * Données des films avec les vrais fichiers de public/pictures/films et public/pictures/hero
     */
    private const MOVIES = [
        [
            'title' => 'Interstellar',
            'description' => 'Un voleur expérimenté dans l\'art de l\'extraction des rêves est engagé pour implanter une idée dans l\'esprit d\'un dirigeant.',
            'duration' => 148,
            'releaseDate' => '2010-07-16',
            'posterUrl' => 'in1-1746527404-1747258966.webp',
            'heroImage' => 'in2-1747258966.jpg',
            'genre' => ['Science-fiction', 'Thriller'],
            'language' => ['VO', 'VF'],
            'ageRating' => '12',
            'rating' => 8.8,
            'atCinema' => true,
        ],
        [
            'title' => 'Dune',
            'description' => 'Paul Atréides et sa famille se voient confier la garde de la planète Arrakis, seule source de l\'épice, la ressource la plus précieuse de l\'univers.',
            'duration' => 155,
            'releaseDate' => '2021-09-15',
            'posterUrl' => 'du1-1747961881.webp',
            'heroImage' => 'du2-1747961881.jpg',
            'genre' => ['Science-fiction', 'Aventure'],
            'language' => ['VO'],
            'ageRating' => '12',
            'rating' => 8.2,
            'atCinema' => true,
        ],
        [
            'title' => 'Oppenheimer',
            'description' => 'L\'histoire du physicien J. Robert Oppenheimer et son rôle dans la création de la bombe atomique.',
            'duration' => 180,
            'releaseDate' => '2023-07-21',
            'posterUrl' => 'op1-1747962066.webp',
            'heroImage' => 'op2-1747962066.jpg',
            'genre' => ['Drame', 'Historique'],
            'language' => ['VO', 'VF'],
            'ageRating' => '12',
            'rating' => 8.5,
            'atCinema' => true,
        ],
        [
            'title' => 'The Dark Knight',
            'description' => 'Batman doit affronter le Joker, un criminel qui sème le chaos à Gotham City.',
            'duration' => 152,
            'releaseDate' => '2008-07-18',
            'posterUrl' => 'th-1747259256.jpg',
            'heroImage' => 'th-1747259256-1750160400.jpg',
            'genre' => ['Action', 'Thriller'],
            'language' => ['VO'],
            'ageRating' => '12',
            'rating' => 9.0,
            'atCinema' => true,
        ],
        [
            'title' => 'Novocaïne',
            'description' => 'James Bond a quitté le service actif. Son repos est interrompu quand un vieil ami lui demande de l\'aide pour une mission périlleuse.',
            'duration' => 163,
            'releaseDate' => '2021-09-30',
            'posterUrl' => 'no1-1746527687-1747316866.webp',
            'heroImage' => 'no2-1747316866.jpg',
            'genre' => ['Action', 'Espionnage'],
            'language' => ['VO', 'VF'],
            'ageRating' => '12',
            'rating' => 7.4,
            'atCinema' => true,
        ],
        [
            'title' => 'Conclave',
            'description' => 'Un jeune garçon rêve de devenir musicien malgré l\'interdiction de sa famille. Il se retrouve au pays des morts.',
            'duration' => 105,
            'releaseDate' => '2017-11-22',
            'posterUrl' => 'co1-1746479439-1747211737.webp',
            'heroImage' => 'co-1747211737-1750160475.jpg',
            'genre' => ['Animation', 'Familial'],
            'language' => ['VO', 'VF'],
            'ageRating' => 'Tous',
            'rating' => 8.4,
            'atCinema' => true,
        ],
        [
            'title' => 'Fight Club',
            'description' => 'Des courses de rue et des braquages à haute tension entre une équipe de pilotes et la loi.',
            'duration' => 107,
            'releaseDate' => '2009-04-02',
            'posterUrl' => 'fc1-1747258368.webp',
            'heroImage' => 'fc2-1747258368-1750160601.jpg',
            'genre' => ['Action', 'Thriller'],
            'language' => ['VO'],
            'ageRating' => '12',
            'rating' => 6.8,
            'atCinema' => true,
        ],
        [
            'title' => 'Princesse Mononoke',
            'description' => 'Bella Baxter est ramenée à la vie par un scientifique. Elle part à la découverte du monde.',
            'duration' => 141,
            'releaseDate' => '2023-12-08',
            'posterUrl' => 'pm1-1747962437.webp',
            'heroImage' => 'pm1-1747962437.jpg',
            'genre' => ['Comédie', 'Science-fiction'],
            'language' => ['VO'],
            'ageRating' => '16',
            'rating' => 8.2,
            'atCinema' => true,
        ],
        [
            'title' => 'The Lost City',
            'description' => 'Une romancière et son modèle de couverture sont entraînés dans une aventure sur une île exotique.',
            'duration' => 112,
            'releaseDate' => '2022-03-25',
            'posterUrl' => 'tl1-1746527773-1747043091.webp',
            'heroImage' => 'oc2-1747960744-1750159625.jpg',
            'genre' => ['Comédie', 'Aventure'],
            'language' => ['VO', 'VF'],
            'ageRating' => '12',
            'rating' => 6.5,
            'atCinema' => false,
        ],
        [
            'title' => 'Champions',
            'description' => 'Un entraîneur de basket renvoyé doit coacher une équipe de joueurs en situation de handicap intellectuel.',
            'duration' => 124,
            'releaseDate' => '2023-03-15',
            'posterUrl' => 'cm1-1747259426.webp',
            'heroImage' => 'cm2-1747259426.webp',
            'genre' => ['Comédie', 'Drame'],
            'language' => ['VF'],
            'ageRating' => 'Tous',
            'rating' => 7.1,
            'atCinema' => true,
        ],
        // Films supplémentaires pour remplir les carrousels (réutilisation des visuels existants)
        ['title' => 'Interstellar', 'description' => 'Des explorateurs voyagent à travers un trou de ver dans l\'espace.', 'duration' => 169, 'releaseDate' => '2014-11-05', 'posterUrl' => 'in1-1746527404-1747258966.webp', 'heroImage' => 'in2-1747258966.jpg', 'genre' => ['Science-fiction', 'Drame'], 'language' => ['VO'], 'ageRating' => '12', 'rating' => 8.6, 'atCinema' => true],
        ['title' => 'Le Roi lion', 'description' => 'Un jeune lion reprend sa place de roi dans la savane.', 'duration' => 88, 'releaseDate' => '2019-07-17', 'posterUrl' => 'co1-1746479439-1747211737.webp', 'heroImage' => 'co-1747211737-1750160475.jpg', 'genre' => ['Animation', 'Familial', 'Aventure'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 6.9, 'atCinema' => true],
        ['title' => 'Avatar', 'description' => 'Un marine est envoyé sur une lune lointaine et se retrouve au cœur d\'un conflit.', 'duration' => 162, 'releaseDate' => '2009-12-16', 'posterUrl' => 'du1-1747961881.webp', 'heroImage' => 'du2-1747961881.jpg', 'genre' => ['Science-fiction', 'Aventure', 'Action'], 'language' => ['VO', 'VF'], 'ageRating' => '12', 'rating' => 7.9, 'atCinema' => true],
        ['title' => 'Les Indestructibles', 'description' => 'Une famille de super-héros doit sauver le monde.', 'duration' => 115, 'releaseDate' => '2004-11-24', 'posterUrl' => 'fc1-1747258368.webp', 'heroImage' => 'fc2-1747258368-1750160601.jpg', 'genre' => ['Animation', 'Action', 'Familial'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 8.0, 'atCinema' => true],
        ['title' => 'La La Land', 'description' => 'Une histoire d\'amour entre un pianiste et une actrice à Los Angeles.', 'duration' => 128, 'releaseDate' => '2016-12-25', 'posterUrl' => 'pm1-1747962437.webp', 'heroImage' => 'pm1-1747962437.jpg', 'genre' => ['Comédie', 'Drame'], 'language' => ['VO', 'VF'], 'ageRating' => 'Tous', 'rating' => 8.0, 'atCinema' => true],
        ['title' => 'Gladiator', 'description' => 'Un général romain réduit en esclavage devient gladiateur.', 'duration' => 155, 'releaseDate' => '2000-05-01', 'posterUrl' => 'op1-1747962066.webp', 'heroImage' => 'op2-1747962066.jpg', 'genre' => ['Action', 'Drame', 'Historique'], 'language' => ['VO'], 'ageRating' => '12', 'rating' => 8.5, 'atCinema' => true],
        ['title' => 'Shutter Island', 'description' => 'Un marshal enquête sur la disparition d\'une patiente dans un hôpital psychiatrique.', 'duration' => 138, 'releaseDate' => '2010-02-19', 'posterUrl' => 'th-1747259256.jpg', 'heroImage' => 'th-1747259256-1750160400.jpg', 'genre' => ['Thriller', 'Drame'], 'language' => ['VO'], 'ageRating' => '16', 'rating' => 8.2, 'atCinema' => true],
        ['title' => 'Toy Story', 'description' => 'Les jouets d\'un enfant prennent vie quand personne ne regarde.', 'duration' => 81, 'releaseDate' => '1995-11-22', 'posterUrl' => 'tl1-1746527773-1747043091.webp', 'heroImage' => 'oc2-1747960744-1750159625.jpg', 'genre' => ['Animation', 'Familial', 'Comédie'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 8.3, 'atCinema' => true],
        ['title' => 'Mission impossible', 'description' => 'Un agent doit démasquer un traître au sein de la CIA.', 'duration' => 110, 'releaseDate' => '1996-05-22', 'posterUrl' => 'no1-1746527687-1747316866.webp', 'heroImage' => 'no2-1747316866.jpg', 'genre' => ['Action', 'Thriller'], 'language' => ['VO'], 'ageRating' => '12', 'rating' => 7.1, 'atCinema' => true],
        ['title' => 'Forrest Gump', 'description' => 'L\'histoire extraordinaire d\'un homme simple au cœur des événements américains.', 'duration' => 142, 'releaseDate' => '1994-07-06', 'posterUrl' => 'cm1-1747259426.webp', 'heroImage' => 'cm2-1747259426.webp', 'genre' => ['Drame', 'Comédie'], 'language' => ['VO', 'VF'], 'ageRating' => '12', 'rating' => 8.8, 'atCinema' => true],
        ['title' => 'Le Voyage de Chihiro', 'description' => 'Une fillette doit sauver ses parents dans un monde de esprits.', 'duration' => 125, 'releaseDate' => '2001-07-20', 'posterUrl' => 'co1-1746479439-1747211737.webp', 'heroImage' => 'co-1747211737-1750160475.jpg', 'genre' => ['Animation', 'Familial', 'Aventure'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 8.6, 'atCinema' => true],
        ['title' => 'Django Unchained', 'description' => 'Un esclave et un chasseur de primes s\'allient dans le Sud des États-Unis.', 'duration' => 165, 'releaseDate' => '2012-12-25', 'posterUrl' => 'th-1747259256.jpg', 'heroImage' => 'th-1747259256-1750160400.jpg', 'genre' => ['Drame', 'Action', 'Historique'], 'language' => ['VO'], 'ageRating' => '16', 'rating' => 8.4, 'atCinema' => true],
        ['title' => 'Mad Max: Fury Road', 'description' => 'Une guerrière et un survivant fuient un tyran dans le désert.', 'duration' => 120, 'releaseDate' => '2015-05-14', 'posterUrl' => 'fc1-1747258368.webp', 'heroImage' => 'fc2-1747258368-1750160601.jpg', 'genre' => ['Action', 'Science-fiction'], 'language' => ['VO'], 'ageRating' => '12', 'rating' => 8.1, 'atCinema' => true],
        ['title' => 'Vice', 'description' => 'Portrait de Dick Cheney, vice-président des États-Unis.', 'duration' => 132, 'releaseDate' => '2018-12-25', 'posterUrl' => 'op1-1747962066.webp', 'heroImage' => 'op2-1747962066.jpg', 'genre' => ['Drame', 'Historique', 'Comédie'], 'language' => ['VO'], 'ageRating' => '12', 'rating' => 7.2, 'atCinema' => true],
        ['title' => 'Kung Fu Panda', 'description' => 'Un panda maladroit est choisi pour sauver la vallée.', 'duration' => 92, 'releaseDate' => '2008-06-04', 'posterUrl' => 'tl1-1746527773-1747043091.webp', 'heroImage' => 'oc2-1747960744-1750159625.jpg', 'genre' => ['Animation', 'Comédie', 'Familial', 'Aventure'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 7.6, 'atCinema' => true],
        ['title' => 'Sully', 'description' => 'Le pilote qui a posé son avion sur l\'Hudson raconte son histoire.', 'duration' => 96, 'releaseDate' => '2016-09-09', 'posterUrl' => 'in1-1746527404-1747258966.webp', 'heroImage' => 'in2-1747258966.jpg', 'genre' => ['Drame', 'Historique'], 'language' => ['VO'], 'ageRating' => 'Tous', 'rating' => 7.4, 'atCinema' => true],
        // Pour atteindre 6 films par section : 1 Aventure, 1 Thriller, 1 Historique
        ['title' => 'Indiana Jones', 'description' => 'Un archéologue part à la recherche d\'artefacts légendaires.', 'duration' => 122, 'releaseDate' => '2023-06-28', 'posterUrl' => 'no1-1746527687-1747316866.webp', 'heroImage' => 'no2-1747316866.jpg', 'genre' => ['Aventure', 'Action'], 'language' => ['VO', 'VF'], 'ageRating' => '12', 'rating' => 6.7, 'atCinema' => true],
        ['title' => 'Seven', 'description' => 'Deux détectives traquent un tueur qui s\'inspire des sept péchés capitaux.', 'duration' => 127, 'releaseDate' => '1995-09-22', 'posterUrl' => 'th-1747259256.jpg', 'heroImage' => 'th-1747259256-1750160400.jpg', 'genre' => ['Thriller', 'Drame'], 'language' => ['VO'], 'ageRating' => '16', 'rating' => 8.6, 'atCinema' => true],
        ['title' => 'Le Discours d\'un roi', 'description' => 'Le roi George VI doit surmonter son bégaiement pour mener son pays à la guerre.', 'duration' => 118, 'releaseDate' => '2010-12-24', 'posterUrl' => 'cm1-1747259426.webp', 'heroImage' => 'cm2-1747259426.webp', 'genre' => ['Drame', 'Historique'], 'language' => ['VO', 'VF'], 'ageRating' => 'Tous', 'rating' => 8.1, 'atCinema' => true],
        // Démo : 10 films par section genre — films supplémentaires
        ['title' => 'Blade Runner 2049', 'description' => 'Un officier découvre un secret enfoui qui pourrait plonger la société dans le chaos.', 'duration' => 164, 'releaseDate' => '2017-10-06', 'posterUrl' => 'in1-1746527404-1747258966.webp', 'heroImage' => 'in2-1747258966.jpg', 'genre' => ['Science-fiction', 'Thriller', 'Drame'], 'language' => ['VO'], 'ageRating' => '12', 'rating' => 8.0, 'atCinema' => true],
        ['title' => 'Le Sens de la fête', 'description' => 'Un wedding planner doit gérer le chaos d\'un mariage de rêve.', 'duration' => 117, 'releaseDate' => '2017-10-04', 'posterUrl' => 'pm1-1747962437.webp', 'heroImage' => 'pm1-1747962437.jpg', 'genre' => ['Comédie', 'Drame'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 7.4, 'atCinema' => true],
        ['title' => 'Wall-E', 'description' => 'Un petit robot nettoie une Terre abandonnée et tombe amoureux.', 'duration' => 98, 'releaseDate' => '2008-06-27', 'posterUrl' => 'co1-1746479439-1747211737.webp', 'heroImage' => 'co-1747211737-1750160475.jpg', 'genre' => ['Animation', 'Science-fiction', 'Familial'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 8.4, 'atCinema' => true],
        ['title' => 'Pirates des Caraïbes', 'description' => 'Un forgeron et un pirate s\'allient pour sauver une jeune femme.', 'duration' => 143, 'releaseDate' => '2003-07-09', 'posterUrl' => 'tl1-1746527773-1747043091.webp', 'heroImage' => 'oc2-1747960744-1750159625.jpg', 'genre' => ['Aventure', 'Action', 'Familial'], 'language' => ['VO', 'VF'], 'ageRating' => '12', 'rating' => 7.6, 'atCinema' => true],
        ['title' => 'Zodiac', 'description' => 'Un cartooniste et des enquêteurs traquent le tueur du Zodiaque.', 'duration' => 157, 'releaseDate' => '2007-03-02', 'posterUrl' => 'th-1747259256.jpg', 'heroImage' => 'th-1747259256-1750160400.jpg', 'genre' => ['Thriller', 'Drame', 'Historique'], 'language' => ['VO'], 'ageRating' => '16', 'rating' => 7.7, 'atCinema' => true],
        ['title' => 'Lincoln', 'description' => 'Le président Lincoln œuvre pour l\'abolition de l\'esclavage.', 'duration' => 150, 'releaseDate' => '2012-11-09', 'posterUrl' => 'op1-1747962066.webp', 'heroImage' => 'op2-1747962066.jpg', 'genre' => ['Drame', 'Historique'], 'language' => ['VO'], 'ageRating' => '12', 'rating' => 7.3, 'atCinema' => true],
        ['title' => 'Ratatouille', 'description' => 'Un rat rêve de devenir chef cuisinier à Paris.', 'duration' => 111, 'releaseDate' => '2007-06-29', 'posterUrl' => 'cm1-1747259426.webp', 'heroImage' => 'cm2-1747259426.webp', 'genre' => ['Animation', 'Comédie', 'Familial'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 8.1, 'atCinema' => true],
        ['title' => 'Matrix', 'description' => 'Un hacker découvre que la réalité n\'est qu\'une simulation.', 'duration' => 136, 'releaseDate' => '1999-03-31', 'posterUrl' => 'du1-1747961881.webp', 'heroImage' => 'du2-1747961881.jpg', 'genre' => ['Science-fiction', 'Action'], 'language' => ['VO', 'VF'], 'ageRating' => '12', 'rating' => 8.7, 'atCinema' => true],
        ['title' => 'Bienvenue chez les Ch\'tis', 'description' => 'Un facteur du Sud est muté dans le Nord.', 'duration' => 106, 'releaseDate' => '2008-02-20', 'posterUrl' => 'no1-1746527687-1747316866.webp', 'heroImage' => 'no2-1747316866.jpg', 'genre' => ['Comédie', 'Drame'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 7.2, 'atCinema' => true],
        ['title' => 'Le Monde de Nemo', 'description' => 'Un poisson-clown part à la recherche de son fils.', 'duration' => 100, 'releaseDate' => '2003-05-30', 'posterUrl' => 'fc1-1747258368.webp', 'heroImage' => 'fc2-1747258368-1750160601.jpg', 'genre' => ['Animation', 'Aventure', 'Familial'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 8.1, 'atCinema' => true],
        ['title' => 'Le Seigneur des anneaux', 'description' => 'Un hobbit doit détruire un anneau maléfique en Mordor.', 'duration' => 178, 'releaseDate' => '2001-12-19', 'posterUrl' => 'in1-1746527404-1747258966.webp', 'heroImage' => 'in2-1747258966.jpg', 'genre' => ['Aventure', 'Fantastique', 'Action'], 'language' => ['VO', 'VF'], 'ageRating' => '12', 'rating' => 8.8, 'atCinema' => true],
        ['title' => 'Gone Girl', 'description' => 'Un homme devient le suspect numéro un quand sa femme disparaît.', 'duration' => 149, 'releaseDate' => '2014-10-03', 'posterUrl' => 'th-1747259256.jpg', 'heroImage' => 'th-1747259256-1750160400.jpg', 'genre' => ['Thriller', 'Drame'], 'language' => ['VO'], 'ageRating' => '16', 'rating' => 8.1, 'atCinema' => true],
        ['title' => 'Les Figures de l\'ombre', 'description' => 'Trois mathématiciennes afro-américaines à la NASA en pleine course spatiale.', 'duration' => 127, 'releaseDate' => '2016-12-25', 'posterUrl' => 'op1-1747962066.webp', 'heroImage' => 'op2-1747962066.jpg', 'genre' => ['Drame', 'Historique'], 'language' => ['VO', 'VF'], 'ageRating' => 'Tous', 'rating' => 7.8, 'atCinema' => true],
        ['title' => 'Shrek', 'description' => 'Un ogre accepte de sauver une princesse pour récupérer son marais.', 'duration' => 90, 'releaseDate' => '2001-04-22', 'posterUrl' => 'tl1-1746527773-1747043091.webp', 'heroImage' => 'oc2-1747960744-1750159625.jpg', 'genre' => ['Animation', 'Comédie', 'Familial', 'Aventure'], 'language' => ['VF'], 'ageRating' => 'Tous', 'rating' => 7.9, 'atCinema' => true],
        ['title' => 'The Truman Show', 'description' => 'Un homme découvre que sa vie est un reality show.', 'duration' => 103, 'releaseDate' => '1998-06-05', 'posterUrl' => 'pm1-1747962437.webp', 'heroImage' => 'pm1-1747962437.jpg', 'genre' => ['Comédie', 'Drame', 'Science-fiction'], 'language' => ['VO'], 'ageRating' => 'Tous', 'rating' => 8.2, 'atCinema' => true],
        ['title' => 'Le Labyrinthe de Pan', 'description' => 'Une fillette découvre un royaume souterrain pendant la guerre d\'Espagne.', 'duration' => 118, 'releaseDate' => '2006-10-11', 'posterUrl' => 'co1-1746479439-1747211737.webp', 'heroImage' => 'co-1747211737-1750160475.jpg', 'genre' => ['Drame', 'Historique', 'Thriller'], 'language' => ['VO', 'VF'], 'ageRating' => '16', 'rating' => 8.2, 'atCinema' => true],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::MOVIES as $index => $data) {
            $movie = new Movie();
            $movie->setTitle($data['title']);
            $movie->setDescription($data['description']);
            $movie->setDuration($data['duration']);
            $movie->setReleaseDate(new DateTime($data['releaseDate']));
            $movie->setLanguage($data['language']);
            $movie->setGenre($data['genre']);
            $movie->setAgeRating($data['ageRating']);
            $movie->setPosterUrl($data['posterUrl']);
            $movie->setHeroImage($data['heroImage']);
            $movie->setIsFavorite($index < 3);
            $movie->setRating($data['rating']);
            $movie->setCreatedAt(new DateTimeImmutable());
            $movie->setAtCinema($data['atCinema']);

            $manager->persist($movie);
            $ref = $index === 0 ? 'movie-inception' : 'movie-' . ($index + 1);
            $this->addReference($ref, $movie);
        }

        $manager->flush();
    }
}
