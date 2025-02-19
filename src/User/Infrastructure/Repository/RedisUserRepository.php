<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\User;
use App\User\Domain\UserRepository;
use Predis\Client;

class RedisUserRepository implements UserRepository
{
    private const REDIS_KEY = 'users';
    private const GENDER_COUNT_KEY = 'gender_count';

    public function __construct(private Client $redis) {}

    public function save(User $user): void
    {
        $users = $this->getAllUsers();
        $users[] = [
            'id' => $user->getUuid(),
            'name' => $user->getName(),
            'lastname' => $user->getLastName(),
            'gender' => $user->getGender(),
            'email' => $user->getEmail(),
        ];

        $this->redis->set(self::REDIS_KEY, json_encode($users));
    }

    public function getAllUsers(): array
    {
        $data = $this->redis->get(self::REDIS_KEY);
        return $data ? json_decode($data, true) : [];
    }

    public function getGenderCount(): array
    {
        $data = $this->redis->get(self::GENDER_COUNT_KEY);
        return $data ? json_decode($data, true) : [];
    }

    public function saveGenderCount(array $genderCount): void
    {
        $this->redis->set(self::GENDER_COUNT_KEY, json_encode($genderCount));
    }
}
