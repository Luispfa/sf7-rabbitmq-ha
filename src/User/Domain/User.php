<?php

declare(strict_types=1);

namespace App\User\Domain;

use App\User\Domain\Event\UserRegisteredEvent;
use App\User\Domain\Event\UserEmailSentEvent;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Gender;
use App\User\Domain\ValueObject\LastName;
use App\User\Domain\ValueObject\Name;
use App\User\Domain\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

class User
{
    private array $domainEvents = [];

    public function __construct(
        private readonly Name $name,
        private readonly LastName $lastName,
        private readonly Gender $gender,
        private readonly Email $email,
        private readonly Uuid $uuid
    ) {}

    public static function create(
        string $name,
        string $lastname,
        string $gender,
        string $email,
    ): static {
        $user = new static(
            name: Name::create($name),
            lastName: LastName::create($lastname),
            gender: Gender::create($gender),
            email: Email::create($email),
            uuid: Uuid::create(RamseyUuid::uuid4()->toString())
        );

        // Accumulate event instead of dispatching it here
        $user->recordDomainEvent(UserRegisteredEvent::fromUser($user));
        $user->recordDomainEvent(UserEmailSentEvent::fromUser($user));

        return $user;
    }

    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function lastName(): LastName
    {
        return $this->lastName;
    }

    public function gender(): Gender
    {
        return $this->gender;
    }

    public function fullName(): string
    {
        return sprintf('%s %s', $this->name->value(), $this->lastName->value());
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid->value(),
            'name' => $this->name->value(),
            'lastname' => $this->lastName->value(),
            'gender' => $this->gender->value(),
            'email' => $this->email->value(),
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
