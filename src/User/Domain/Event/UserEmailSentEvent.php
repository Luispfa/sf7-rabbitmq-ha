<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\User\Domain\User;

class UserEmailSentEvent
{
    private string $userId;
    private string $email;

    private function __construct(string $userId, string $email)
    {
        $this->userId = $userId;
        $this->email = $email;
    }

    public static function fromUser(User $user): self
    {
        return new self($user->getUuid(), $user->getEmail());
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
}
