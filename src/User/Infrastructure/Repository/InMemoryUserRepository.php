<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\User;
use App\User\Domain\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    private static ?string $file = null;

    public function __construct()
    {
        if (null === self::$file) {
            self::$file = $_ENV['USERS_JSON_FILE'];
        }
    }

    public function save(User $user): void
    {
        $users = $this->getAllUsers();
        $users[$user->getUuid()] = $user->toArray();

        file_put_contents(self::$file, json_encode($users));
    }

    public function getAllUsers(): array
    {
        if (!file_exists(self::$file)) {
            return [];
        }
        $data = file_get_contents(self::$file);
        $users = json_decode($data, true);
        return is_array($users) ? $users : [];
    }
}
