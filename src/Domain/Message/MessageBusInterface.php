<?php

declare(strict_types=1);

namespace App\Domain\Message;

interface MessageBusInterface
{
    public function dispatch(Message $message): void;
}
