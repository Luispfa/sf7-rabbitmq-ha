<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Message\Message;
use App\Domain\Message\MessageBus;

class MessageService
{
    public function __construct(private MessageBus $bus) {}

    public function sendMessage(string $content): void
    {
        $message = Message::create($content);
        $this->bus->dispatch($message);
    }
}
