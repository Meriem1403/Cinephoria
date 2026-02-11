<?php
// src/Controller/QrCodeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCodeBundle\Response\QrCodeResponse;

class QrCodeController extends AbstractController
{
    #[Route(
        '/qr/{text}',
        name: 'qr_code_route',
        requirements: ['text' => '.+'],  // pour autoriser les caractères spéciaux dans {text}
        methods: ['GET']
    )]
    public function qr(string $text, BuilderInterface $qrCodeBuilder): Response
    {
        // on construit le QR code avec le builder injecté :contentReference[oaicite:0]{index=0}
        $qrCode = $qrCodeBuilder->build(
            data:   $text,
            size:   300,
            margin: 10
        );

        // on renvoie directement une réponse image grâce à QrCodeResponse :contentReference[oaicite:1]{index=1}
        return new QrCodeResponse($qrCode);
    }
}
