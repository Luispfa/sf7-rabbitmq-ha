<?php

declare(strict_types=1);

namespace App\Domain\Message;

class Message
{
    public function __construct(private string $content) {}

    public function getContent(): string
    {
        return $this->content;
    }

    public static function create(string $content): static
    {
        return new static($content);
    }
}
