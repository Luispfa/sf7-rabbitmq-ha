<?php

declare(strict_types=1);

namespace App\User\Domain;

use App\User\Domain\Event\UserRegisteredEvent;
use App\User\Domain\Event\UserEmailSentEvent;
use Ramsey\Uuid\Uuid as RamseyUuid;

class User
{
    private array $domainEvents = [];

    private function __construct(
        private string $uuid,
        private string $name,
        private string $lastname,
        private string $gender,
        private string $email,
    ) {}

    public static function create(
        string $name,
        string $lastname,
        string $gender,
        string $email,
    ): static {
        $uuid = RamseyUuid::uuid4()->toString();
        $user = new static($uuid, $name, $lastname, $gender, $email);

        // Accumulate event instead of dispatching it here
        $user->recordDomainEvent(UserRegisteredEvent::fromUser($user));
        $user->recordDomainEvent(UserEmailSentEvent::fromUser($user));


        return $user;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'gender' => $this->gender,
            'email' => $this->email,
        ];
    }

    public function recordDomainEvent(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function releaseDomainEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }
}
