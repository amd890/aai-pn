<?php

namespace App\Support\Enums;

enum MemberStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
    case Expired = 'expired';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Verifikasi',
            self::Active => 'Aktif',
            self::Inactive => 'Tidak Aktif',
            self::Suspended => 'Ditangguhkan',
            self::Expired => 'Kadaluarsa',
            self::Rejected => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Active => 'green',
            self::Inactive => 'gray',
            self::Suspended => 'orange',
            self::Expired => 'red',
            self::Rejected => 'red',
        };
    }
}
