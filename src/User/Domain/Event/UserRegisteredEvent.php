<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\User\Domain\User;

class UserRegisteredEvent
{
    private string $userId;
    private string $gender;

    private function __construct(string $userId, string $gender)
    {
        $this->userId = $userId;
        $this->gender = $gender;
    }

    public static function fromUser(User $user): self
    {
        return new self($user->getUuid(), $user->getGender());
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
    public function getGender(): string
    {
        return $this->gender;
    }
}
