<?php

declare(strict_types=1);

namespace App\Infrastructure\RabbitMQ;

use App\Domain\Message\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface as SymfonyMessageBus;

class RabbitMQMessageBus implements MessageBus
{
    public function __construct(private SymfonyMessageBus $messageBus) {}

    public function dispatch(object $message): void
    {
        $this->messageBus->dispatch($message);
    }
}
