<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Message\Message;
use App\Domain\Message\MessageBusInterface;

class MessageService
{
    public function __construct(private MessageBusInterface $messageBus) {}

    public function sendMessage(string $content): void
    {
        $message = Message::create($content);
        $this->messageBus->dispatch($message);
    }
}
