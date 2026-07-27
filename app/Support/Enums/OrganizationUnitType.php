<?php

namespace App\Support\Enums;

enum OrganizationUnitType: string
{
    case Pusat = 'pusat';
    case Wilayah = 'wilayah';
    case Cabang = 'cabang';

    public function label(): string
    {
        return match ($this) {
            self::Pusat => 'Pengurus Pusat',
            self::Wilayah => 'Pengurus Wilayah',
            self::Cabang => 'Pengurus Cabang',
        };
    }
}
