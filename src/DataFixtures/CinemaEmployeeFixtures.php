<?php

namespace App\DataFixtures;

use App\Entity\CinemaEmployee;
use App\Entity\User;
use App\Entity\Cinema;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CinemaEmployeeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $employee = new CinemaEmployee();
        $employee->setUser($this->getReference('employee-user-1', User::class));
        $employee->setCinema($this->getReference('cinema-1', Cinema::class));
        $employee->setJobTitle('Agent d’accueil');
        $employee->setAssignedSince(new DateTime('2023-07-01'));
        $employee->setIsActive(true);

        $manager->persist($employee);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CinemaFixtures::class,
        ];
    }
}
