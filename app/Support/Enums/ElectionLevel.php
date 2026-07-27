<?php

namespace App\Support\Enums;

enum ElectionLevel: string
{
    case Nasional = 'nasional';
    case Wilayah = 'wilayah';
    case Cabang = 'cabang';

    public function label(): string
    {
        return match ($this) {
            self::Nasional => 'Nasional (Munas)',
            self::Wilayah => 'Wilayah (Muswil)',
            self::Cabang => 'Cabang (Muscab)',
        };
    }
}
