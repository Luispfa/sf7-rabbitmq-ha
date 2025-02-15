<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\User\User;
use App\Domain\User\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[$user->getUuid()] = $user->toArray();
    }

    public function getAllUsers(): array
    {
        return $this->users;
    }
}
