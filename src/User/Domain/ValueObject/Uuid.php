<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

final class Uuid
{
    private string $value;

    private function __construct(string $uuid)
    {
        if (!RamseyUuid::isValid($uuid)) {
            throw new \InvalidArgumentException('Invalid UUID format');
        }

        $this->value = $uuid;
    }

    public static function create(string $uuid): self
    {
        return new self($uuid);
    }

    public static function generate(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value === $other->value;
    }
}
