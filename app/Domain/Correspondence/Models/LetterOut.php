<?php

namespace App\Domain\Correspondence\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterOut extends BaseModel
{
    use SoftDeletes;

    protected $table = 'letters_out';

    protected $fillable = [
        'letter_number', 'recipient', 'recipient_institution', 'subject',
        'content', 'letter_date', 'template_id', 'file_path',
        'signed_by', 'signer_position', 'qr_code', 'classification',
        'organization_unit_id', 'status', 'created_by', 'notes',
    ];

    protected function casts(): array
    {
        return ['letter_date' => 'date'];
    }

    protected array $searchable = ['letter_number', 'recipient', 'subject'];

    public function organizationUnit(): BelongsTo { return $this->belongsTo(OrganizationUnit::class); }
    public function signer(): BelongsTo { return $this->belongsTo(User::class, 'signed_by'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function scopeDraft($query) { return $query->where('status', 'draft'); }
    public function scopeSigned($query) { return $query->where('status', 'signed'); }
}
