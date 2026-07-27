<?php

namespace App\Domain\Membership\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberCertificate extends BaseModel
{
    protected $fillable = [
        'member_id', 'certificate_number', 'type', 'qr_code',
        'signed_by', 'signer_position', 'issued_at', 'file_path',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
