<?php

namespace App\Support\Enums;

enum DueStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Overdue = 'overdue';
    case Waived = 'waived';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Belum Dibayar',
            self::Paid => 'Lunas',
            self::Overdue => 'Jatuh Tempo',
            self::Waived => 'Dibebaskan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Paid => 'green',
            self::Overdue => 'red',
            self::Waived => 'blue',
        };
    }
}
