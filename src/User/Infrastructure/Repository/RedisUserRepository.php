<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\User;
use App\User\Domain\UserRepository;
use Predis\Client;

class RedisUserRepository implements UserRepository
{
    private const string REDIS_KEY = 'users';

    public function __construct(
        private readonly Client $redis
    ) {}

    public function save(User $user): void
    {
        $users = $this->getAllUsers();
        $users[] = $user->toArray();

        $this->redis->set(self::REDIS_KEY, json_encode($users, JSON_PRETTY_PRINT));
    }

    public function getAllUsers(): array
    {
        $data = $this->redis->get(self::REDIS_KEY);
        return $data ? json_decode($data, true) : [];
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
