<?php

declare(strict_types=1);

namespace App\User\Infrastructure\EventHandler;

use App\User\Domain\Event\UserEmailSentEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserEmailSentEventHandler
{
    public function __invoke(UserEmailSentEvent $event): void
    {
        // Simulamos el envío de correo
        echo sprintf("📧 Enviando email a: %s\n", $event->getEmail());

        // Aquí puedes integrar un servicio real (Mailer, AWS SES, etc.)
    }
}
