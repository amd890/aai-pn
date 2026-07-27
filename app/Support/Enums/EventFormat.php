<?php

namespace App\Support\Enums;

enum EventFormat: string
{
    case Offline = 'offline';
    case Online = 'online';
    case Hybrid = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::Offline => 'Luring (Offline)',
            self::Online => 'Daring (Online)',
            self::Hybrid => 'Hybrid',
        };
    }
}
