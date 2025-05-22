<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_settings')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserSettingsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour la langue (dans la session, pas dans l'entité)
            $language = $form->get('siteLanguage')->getData();
            if ($language) {
                $request->getSession()->set('_locale', $language);
            }

            $em->flush();

            $this->addFlash('success', 'Your settings have been updated.');
            return $this->redirectToRoute('app_settings');
        }

        return $this->render('account/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
