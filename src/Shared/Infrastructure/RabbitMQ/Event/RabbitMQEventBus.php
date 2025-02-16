<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\RabbitMQ\Event;

use App\Shared\Domain\Event\DomainEventBus;
use Symfony\Component\Messenger\MessageBusInterface;

class RabbitMQEventBus implements DomainEventBus
{
    public function __construct(private MessageBusInterface $bus) {}

    public function publish(object $event): void
    {
        $this->bus->dispatch($event);
    }
}
