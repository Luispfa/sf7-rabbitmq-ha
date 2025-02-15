<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Domain\Message\GenderCountMessage;
use App\Domain\Message\MessageBus;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private MessageBus $bus
    ) {}

    public function registerUser(string $name, string $lastname, string $gender): array
    {
        $uuid = RamseyUuid::uuid4()->toString();
        $user = User::create($uuid, $name, $lastname, $gender);
        $this->userRepository->save($user);

        $this->bus->dispatch(GenderCountMessage::create($gender));

        return $this->userRepository->getAllUsers();
    }
}
