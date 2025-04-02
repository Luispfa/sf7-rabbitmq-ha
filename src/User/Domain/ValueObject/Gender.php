<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use App\User\Domain\Enum\GenderType;
use App\User\Domain\Exception\InvalidGenderException;

final class Gender
{
    private GenderType $value;

    private function __construct(string $gender)
    {
        try {
            $this->value = GenderType::from($gender);
        } catch (\ValueError) {
            throw new InvalidGenderException();
        }
    }

    public static function create(string $gender): self
    {
        return new self($gender);
    }

    public function value(): string
    {
        return $this->value->value;
    }

    public function equals(Gender $other): bool
    {
        return $this->value === $other->value;
    }
}
