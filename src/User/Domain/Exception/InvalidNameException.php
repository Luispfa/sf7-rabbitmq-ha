<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

class InvalidNameException extends \InvalidArgumentException
{
    public function __construct(string $message = 'Invalid name format or length')
    {
        parent::__construct($message);
    }
}
