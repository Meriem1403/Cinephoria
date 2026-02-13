<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * En prod, écrit l'exception sur stderr pour que Railway (Deploy Logs) l'affiche.
 */
#[AsEventListener(event: KernelEvents::EXCEPTION, priority: -128)]
class ExceptionToStderrListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $msg = sprintf(
            "[Cinephoria 500] %s: %s in %s on line %d\nStack trace:\n%s",
            $throwable::class,
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
            $throwable->getTraceAsString()
        );
        file_put_contents('php://stderr', $msg . "\n");
    }
}
