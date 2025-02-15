<?php

declare(strict_types=1);

namespace App\Infrastructure\RabbitMQ;

use App\Domain\Message\Message;
use App\Domain\Message\MessageBusInterface;
use Symfony\Component\Messenger\MessageBusInterface as SymfonyMessageBus;

class RabbitMQMessageBus implements MessageBusInterface
{
    public function __construct(private SymfonyMessageBus $messageBus) {}

    public function dispatch(Message $message): void
    {
        $this->messageBus->dispatch($message);
    }
}
