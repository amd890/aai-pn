<?php

namespace App\Support\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Verified = 'verified';
    case Rejected = 'rejected';
    case Refunded = 'refunded';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Verifikasi',
            self::Verified => 'Terverifikasi',
            self::Rejected => 'Ditolak',
            self::Refunded => 'Dikembalikan',
            self::Expired => 'Kadaluarsa',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Verified => 'green',
            self::Rejected => 'red',
            self::Refunded => 'blue',
            self::Expired => 'gray',
        };
    }
}
