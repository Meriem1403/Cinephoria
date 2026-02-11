<?php
// src/Controller/DevFixRolesController.php
namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DevFixRolesController extends AbstractController
{
#[Route('/fix-roles', name: 'fix_roles')]
public function fixRoles(UserRepository $repo, EntityManagerInterface $em): Response
{
$users = $repo->findAll();
foreach ($users as $user) {
$roles = $user->getRoles();
if (!is_array($roles) || empty($roles) || $roles === [''] || $roles === [null] || $roles === false) {
$user->setRoles(['ROLE_USER']);
}
}
$em->flush();

return new Response('OK: roles corrigés');
}
}
