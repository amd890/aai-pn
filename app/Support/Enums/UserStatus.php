<?php

namespace App\Support\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
    case Banned = 'banned';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Inactive => 'Tidak Aktif',
            self::Suspended => 'Ditangguhkan',
            self::Banned => 'Diblokir',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Inactive => 'gray',
            self::Suspended => 'yellow',
            self::Banned => 'red',
        };
    }
}
