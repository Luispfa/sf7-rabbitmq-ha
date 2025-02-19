<?php

declare(strict_types=1);

namespace App\User\Infrastructure\EventHandler;

use App\User\Domain\Event\UserRegisteredEvent;
use App\User\Domain\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserRegisteredEventHandler
{
    public function __construct(private UserRepository $userRepository) {}

    public function __invoke(UserRegisteredEvent $event): void
    {
        echo "✅ Processing UserRegisteredEvent for user {$event->getUserId()}...\n";
        // Simulating an error
        // throw new \Exception("❌ Simulated error: Failed to process UserRegisteredEvent.");

        $genderCount = $this->userRepository->getGenderCount();
        // If the gender does not exist, initialize it to 0
        if (!isset($genderCount[$event->getGender()])) {
            $genderCount[$event->getGender()] = 0;
        }

        // Increment the counter for the corresponding gender
        $genderCount[$event->getGender()]++;
        $this->userRepository->saveGenderCount($genderCount);

        echo "✅ Successfully processed UserRegisteredEvent for user {$event->getUserId()}.\n";
    }
}
