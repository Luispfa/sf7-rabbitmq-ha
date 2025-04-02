<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use App\User\Domain\Exception\InvalidEmailException;

final class Email
{
    private string $value;

    private function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        $this->value = $email;
    }

    public static function create(string $email): self
    {
        return new self($email);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }
}
