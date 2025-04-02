<?php

declare(strict_types=1);

namespace App\User\Domain\Enum;

enum GenderType: string
{
    case MALE = 'Male';
    case FEMALE = 'Female';
    case OTHER = 'Other';
}
