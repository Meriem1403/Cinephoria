<?php

namespace App\DataFixtures;

use App\Entity\Cinema;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CinemaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cinema = new Cinema();
        $cinema->setName('Cinéphoria Lyon');
        $cinema->setCity('Lyon');
        $cinema->setAdress('8 rue Lumière');
        $cinema->setPostalCode('69001');
        $cinema->setCountry('France');
        $cinema->setPhone('04 78 00 00 00');
        $cinema->setEmail('lyon@cinephoria.fr');

        $manager->persist($cinema);
        $manager->flush();
        $this->addReference('cinema-1', $cinema);
    }
}
