<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

class InvalidLastNameException extends \InvalidArgumentException
{
    public function __construct(string $message = 'Invalid last name format or length')
    {
        parent::__construct($message);
    }
}
