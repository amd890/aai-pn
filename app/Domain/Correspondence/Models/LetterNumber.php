<?php

namespace App\Domain\Correspondence\Models;

use App\Domain\Organization\Models\OrganizationUnit;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterNumber extends BaseModel
{
    protected $fillable = [
        'organization_unit_id', 'format_template',
        'last_number', 'year', 'prefix', 'suffix',
    ];

    protected function casts(): array
    {
        return [
            'last_number' => 'integer',
            'year' => 'integer',
        ];
    }

    public function organizationUnit(): BelongsTo { return $this->belongsTo(OrganizationUnit::class); }

    /**
     * Generate the next letter number based on template.
     */
    public function generateNext(): string
    {
        $this->increment('last_number');

        $replacements = [
            '{no}' => str_pad($this->last_number, 3, '0', STR_PAD_LEFT),
            '{tahun}' => $this->year,
            '{prefix}' => $this->prefix ?? '',
            '{suffix}' => $this->suffix ?? '',
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->format_template
        );
    }
}
