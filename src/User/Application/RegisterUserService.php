<?php

declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\User;
use App\User\Domain\UserRepository;
use App\Shared\Domain\Event\DomainEventBus;

class RegisterUserService
{
    public function __construct(
        private UserRepository $userRepository,
        private DomainEventBus $eventBus
    ) {}

    public function __invoke(string $name, string $lastname, string $gender): array
    {
        $user = User::create($name, $lastname, $gender);
        $this->userRepository->save($user);

        // Dispatch events from the application, not from the domain
        foreach ($user->releaseDomainEvents() as $event) {
            $this->eventBus->publish($event);
        }


        return $this->userRepository->getAllUsers();
    }
}
