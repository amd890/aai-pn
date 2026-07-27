<?php

namespace App\Support\Enums;

enum RegionType: string
{
    case Provinsi = 'provinsi';
    case Kabupaten = 'kabupaten';
    case Kecamatan = 'kecamatan';

    public function label(): string
    {
        return match ($this) {
            self::Provinsi => 'Provinsi',
            self::Kabupaten => 'Kabupaten/Kota',
            self::Kecamatan => 'Kecamatan',
        };
    }
}
