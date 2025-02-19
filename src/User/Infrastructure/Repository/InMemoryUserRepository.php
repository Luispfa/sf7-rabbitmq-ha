<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\User;
use App\User\Domain\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    private static ?string $usersFile = null;
    private static ?string $genderFile = null;

    public function __construct()
    {
        if (null === self::$usersFile) {
            self::$usersFile = $_ENV['USERS_JSON_FILE'];
        }

        if (null === self::$genderFile) {
            self::$genderFile = $_ENV['GENDER_COUNT_JSON_FILE'];
        }
    }

    public function save(User $user): void
    {
        $users = $this->getAllUsers();
        $users[] = $user->toArray();

        file_put_contents(self::$usersFile, json_encode($users));
    }

    public function getAllUsers(): array
    {
        return file_exists(self::$usersFile)
            ? json_decode(file_get_contents(self::$usersFile), true)
            : [];
    }

    public function getGenderCount(): array
    {
        return file_exists(self::$genderFile)
            ? json_decode(file_get_contents(self::$genderFile), true)
            : [];
    }

    public function saveGenderCount(array $genderCount): void
    {
        file_put_contents(self::$genderFile, json_encode($genderCount, JSON_PRETTY_PRINT));
    }
}
