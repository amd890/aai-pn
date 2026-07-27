<?php

namespace App\Domain\Finance\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends BaseModel
{
    protected $fillable = [
        'payment_id', 'invoice_number', 'amount', 'tax', 'total',
        'pdf_path', 'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'issued_at' => 'datetime',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
