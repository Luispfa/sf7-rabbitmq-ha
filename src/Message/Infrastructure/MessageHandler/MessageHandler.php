<?php

declare(strict_types=1);

namespace App\Message\Infrastructure\MessageHandler;

use App\Message\Domain\Message\Message;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MessageHandler
{
    public function __invoke(Message $message)
    {
        echo "Mensaje recibido: " . $message->getContent();
    }
}
