<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\User\Domain\User;
use App\User\Domain\ValueObject\Gender;
use App\User\Domain\ValueObject\Uuid;

class UserRegisteredEvent
{
    private function __construct(
        private readonly Uuid $userId,
        private readonly Gender $gender
    ) {}

    public static function fromUser(User $user): self
    {
        return new self($user->uuid(), $user->gender());
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }
    public function getGender(): Gender
    {
        return $this->gender;
    }
}
