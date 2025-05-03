<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new Role();
        $admin->setName('ROLE_ADMIN');
        $admin->setDescription('Administrateur');
        $manager->persist($admin);
        $this->addReference('role_admin', $admin);

        $employe = new Role();
        $employe->setName('ROLE_EMPLOYE');
        $employe->setDescription('Employé du cinéma');
        $manager->persist($employe);
        $this->addReference('role_employe', $employe);

        $user = new Role();
        $user->setName('ROLE_USER');
        $user->setDescription('Client enregistré');
        $manager->persist($user);
        $this->addReference('role_user', $user);

        $manager->flush();
    }
}