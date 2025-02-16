<?php

declare(strict_types=1);

namespace App\User\Infrastructure\EventHandler;

use App\User\Domain\Event\UserRegisteredEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GenderCountEventHandler
{
    // Simulamos un contador en un archivo para persistencia temporal.
    // En una aplicación real, esto se haría en una base de datos o sistema de cache.
    public function __invoke(UserRegisteredEvent $event): void
    {
        $gender = $event->getGender();
        $file = $_ENV['GENDER_COUNT_JSON_FILE'];

        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
        } else {
            $data = [];
        }

        $data[$gender] = ($data[$gender] ?? 0) + 1;
        file_put_contents($file, json_encode($data));
    }
}
