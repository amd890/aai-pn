<?php

namespace App\Domain\Finance\Models;

use App\Domain\Membership\Models\Member;
use App\Models\BaseModel;
use App\Support\Enums\DueStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Due extends BaseModel
{
    protected $fillable = [
        'member_id', 'period_year', 'period_month', 'amount',
        'status', 'due_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'status' => DueStatus::class,
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', DueStatus::Pending)
            ->where('due_date', '<', now());
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where('period_year', $year);
    }
}
