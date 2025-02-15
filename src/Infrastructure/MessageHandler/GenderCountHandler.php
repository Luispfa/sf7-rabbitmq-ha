<?php

declare(strict_types=1);

namespace App\Infrastructure\MessageHandler;

use App\Domain\Message\GenderCountMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GenderCountHandler
{
    private array $genderCount = [];

    public function __invoke(GenderCountMessage $message): void
    {
        $gender = $message->getGender();
        $this->genderCount[$gender] = ($this->genderCount[$gender] ?? 0) + 1;

        // Simulación de almacenamiento persistente
        file_put_contents('/tmp/gender_count.json', json_encode($this->genderCount));
    }

    public function getGenderCount(): array
    {
        return json_decode(file_get_contents('/tmp/gender_count.json'), true) ?? [];
    }
}
