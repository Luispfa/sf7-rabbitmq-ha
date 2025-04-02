<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use App\User\Domain\Exception\InvalidNameException;

final class Name
{
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 50;
    private string $value;

    private function __construct(string $name)
    {
        if (strlen($name) < self::MIN_LENGTH || strlen($name) > self::MAX_LENGTH) {
            throw new InvalidNameException();
        }

        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $name)) {
            throw new InvalidNameException();
        }

        $this->value = $name;
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Name $other): bool
    {
        return $this->value === $other->value;
    }
}
