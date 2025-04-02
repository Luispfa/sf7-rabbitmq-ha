<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\User\Domain\User;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Uuid;

class UserEmailSentEvent
{
    private function __construct(
        private readonly Uuid $userId,
        private readonly Email $email
    ) {}

    public static function fromUser(User $user): self
    {
        return new self($user->uuid(), $user->email());
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }
    public function getEmail(): Email
    {
        return $this->email;
    }
}
