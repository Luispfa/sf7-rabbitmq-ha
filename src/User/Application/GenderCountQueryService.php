<?php

declare(strict_types=1);

namespace App\User\Application;

class GenderCountQueryService
{
    public function __invoke(): array
    {
        $filePath = $_ENV['GENDER_COUNT_JSON_FILE'];
        if (!file_exists($filePath)) {
            return [];
        }

        $data = file_get_contents($filePath);

        return json_decode($data, true) ?: [];
    }
}
