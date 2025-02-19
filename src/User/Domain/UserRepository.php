<?php

declare(strict_types=1);

namespace App\User\Domain;

interface UserRepository
{
    public function save(User $user): void;

    public function getAllUsers(): array;

    public function getGenderCount(): array;

    public function saveGenderCount(array $genderCount): void;
}
