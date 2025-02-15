<?php

declare(strict_types=1);

namespace App\Domain\Message;

class GenderCountMessage
{
    private string $gender;

    private function __construct(string $gender)
    {
        $this->gender = $gender;
    }

    public static function create(string $gender): static
    {
        return new static($gender);
    }

    public function getGender(): string
    {
        return $this->gender;
    }
}
