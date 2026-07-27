<?php

namespace App\Support\Enums;

enum PaymentMethod: string
{
    case BankTransfer = 'bank_transfer';
    case Gateway = 'gateway';
    case Cash = 'cash';
    case Qris = 'qris';

    public function label(): string
    {
        return match ($this) {
            self::BankTransfer => 'Transfer Bank',
            self::Gateway => 'Payment Gateway',
            self::Cash => 'Tunai',
            self::Qris => 'QRIS',
        };
    }
}
