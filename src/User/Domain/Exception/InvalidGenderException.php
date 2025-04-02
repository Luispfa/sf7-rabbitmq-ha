<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

class InvalidGenderException extends \InvalidArgumentException
{
    public function __construct(string $message = 'Invalid gender value')
    {
        parent::__construct($message);
    }
}
