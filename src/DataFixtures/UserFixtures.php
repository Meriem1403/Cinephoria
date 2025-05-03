<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Role;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Admin
        $admin = new User();
        $admin->setFirstName('Admin');
        $admin->setLastName('Cinéphoria');
        $admin->setEmail('admin@cinephoria.fr');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpass'));
        $admin->setAdress('123 rue du cinéma, Paris');
        $admin->setCreatedAt(new DateTimeImmutable());
        $admin->setIsActive(true);
        $admin->setRole($this->getReference('role_admin', Role::class));
        $manager->persist($admin);
        $this->addReference('admin-user', $admin);

        // Employé
        $employee = new User();
        $employee->setFirstName('Jean');
        $employee->setLastName('Dupont');
        $employee->setEmail('employe1@cinephoria.fr');
        $employee->setPassword($this->passwordHasher->hashPassword($employee, 'employepass'));
        $employee->setAdress('10 avenue des cinémas, Lyon');
        $employee->setCreatedAt(new DateTimeImmutable());
        $employee->setIsActive(true);
        $employee->setRole($this->getReference('role_employe', Role::class));
        $manager->persist($employee);
        $this->addReference('employee-user-1', $employee);

        // Client
        $user = new User();
        $user->setFirstName('Alice');
        $user->setLastName('Martin');
        $user->setEmail('client1@cinephoria.fr');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'userpass'));
        $user->setAdress('42 rue des spectateurs, Lille');
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setIsActive(true);
        $user->setRole($this->getReference('role_user', Role::class));
        $manager->persist($user);
        $this->addReference('user-client-1', $user);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
        ];
    }
}
