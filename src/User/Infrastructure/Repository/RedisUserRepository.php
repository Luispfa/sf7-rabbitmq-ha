<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\User;
use App\User\Domain\UserRepository;
use Predis\Client;

class RedisUserRepository implements UserRepository
{
    private Client $redis;
    private const REDIS_KEY = 'users';

    public function __construct()
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host' => 'sf7_redis_ha',
            'port' => 6379,
        ]);
    }

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
}
