<?php
// src/Security/ApiLoginSuccessHandler.php
namespace App\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiLoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        $user = $token->getUser();

        // renvoyez les données que votre appli mobile attend
        return new JsonResponse([
            'id'         => $user->getId(),
            'email'      => $user->getEmail(),
            'firstName'  => $user->getFirstName(),
            'lastName'   => $user->getLastName(),
            // ajoutez un token JWT ici si vous en générez un
        ], JsonResponse::HTTP_OK);
    }
}
