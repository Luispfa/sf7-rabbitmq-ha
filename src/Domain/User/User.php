<?php

declare(strict_types=1);

namespace App\Domain\User;

class User
{
    private function __construct(
        private string $uuid,
        private string $name,
        private string $lastname,
        private string $gender,
    ) {}

    public static function create(
        string $uuid,
        string $name,
        string $lastname,
        string $gender
    ): static {
        return new static($uuid, $name, $lastname, $gender);
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'gender' => $this->gender,
        ];
    }
}
