<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

class InvalidEmailException extends \InvalidArgumentException
{
    public function __construct(string $message = 'Invalid email format')
    {
        parent::__construct($message);
    }
}
