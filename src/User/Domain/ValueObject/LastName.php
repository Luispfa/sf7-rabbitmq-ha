<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use App\User\Domain\Exception\InvalidLastNameException;

final class LastName
{
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 50;
    private string $value;

    public function __construct(string $lastName)
    {
        if (strlen($lastName) < self::MIN_LENGTH || strlen($lastName) > self::MAX_LENGTH) {
            throw new InvalidLastNameException();
        }

        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $lastName)) {
            throw new InvalidLastNameException();
        }

        $this->value = $lastName;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(LastName $other): bool
    {
        return $this->value === $other->value;
    }
}
