<?php

namespace App\Support\Enums;

enum ElectionStatus: string
{
    case Draft = 'draft';
    case Open = 'open';
    case Closed = 'closed';
    case Counted = 'counted';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf',
            self::Open => 'Dibuka',
            self::Closed => 'Ditutup',
            self::Counted => 'Selesai Dihitung',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Open => 'green',
            self::Closed => 'yellow',
            self::Counted => 'blue',
        };
    }
}
