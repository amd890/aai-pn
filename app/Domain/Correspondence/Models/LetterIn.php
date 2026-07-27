<?php

namespace App\Domain\Correspondence\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterIn extends BaseModel
{
    use SoftDeletes;

    protected $table = 'letters_in';

    protected $fillable = [
        'letter_number', 'sender', 'sender_institution', 'subject',
        'description', 'received_date', 'letter_date', 'file_path',
        'classification', 'disposition', 'organization_unit_id',
        'received_by', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'received_date' => 'date',
            'letter_date' => 'date',
        ];
    }

    protected array $searchable = ['letter_number', 'sender', 'subject'];

    public function organizationUnit(): BelongsTo { return $this->belongsTo(OrganizationUnit::class); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
}
