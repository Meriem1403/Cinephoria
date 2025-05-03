<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $roles = [
            ['name' => 'ROLE_ADMIN', 'description' => 'Administrateur', 'ref' => 'role_admin'],
            ['name' => 'ROLE_EMPLOYE', 'description' => 'Employé du cinéma', 'ref' => 'role_employe'],
            ['name' => 'ROLE_USER', 'description' => 'Client enregistré', 'ref' => 'role_user'],
        ];

        foreach ($roles as $data) {
            $role = new Role();
            $role->setName($data['name']);
            $role->setDescription($data['description']);

            $manager->persist($role);
            $this->addReference($data['ref'], $role);
        }

        $manager->flush();
    }
}
