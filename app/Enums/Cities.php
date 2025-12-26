<?php

namespace App\Enums;

enum Cities: string
{
    case ABUJA = 'Abuja';
    case ENUGU = 'Enugu';
    case LAGOS = 'Lagos';
    case PORT_HARCOURT = 'Port Harcourt';

    public function label(): string
    {
        return match ($this) {
            self::ABUJA => 'Abuja',
            self::ENUGU => 'Enugu',
            self::LAGOS => 'Lagos',
            self::PORT_HARCOURT => 'Port Harcourt',
        };
    }
}
