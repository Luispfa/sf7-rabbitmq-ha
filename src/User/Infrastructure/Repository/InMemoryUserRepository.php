<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\User;
use App\User\Domain\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    private static ?string $usersFile = null;

    public function __construct()
    {
        if (null === self::$usersFile) {
            self::$usersFile = $_ENV['USERS_JSON_FILE'];
        }
    }

    public function save(User $user): void
    {
        $users = $this->getAllUsers();
        $users[] = $user->toArray();

        file_put_contents(self::$usersFile, json_encode($users, JSON_PRETTY_PRINT));
    }

    public function getAllUsers(): array
    {
        return file_exists(self::$usersFile)
            ? json_decode(file_get_contents(self::$usersFile), true)
            : [];
    }

    public function getGenderCount(): array
    {
        $users = $this->getAllUsers();
        $genderCount = [];

        foreach ($users as $user) {
            $gender = $user['gender'];
            $genderCount[$gender] = ($genderCount[$gender] ?? 0) + 1;
        }

        return $genderCount;
    }

    public function saveGenderCount(array $genderCount): void
    {
        // No necesitamos guardar el conteo ya que lo calculamos en tiempo real
    }
}
