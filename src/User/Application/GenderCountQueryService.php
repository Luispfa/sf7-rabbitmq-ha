<?php

declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\UserRepository;

class GenderCountQueryService
{
    public function __construct(private UserRepository $userRepository) {}

    public function __invoke(): array
    {
        return $this->userRepository->getGenderCount();
    }
}
