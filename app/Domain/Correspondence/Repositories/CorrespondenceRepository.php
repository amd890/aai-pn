<?php

namespace App\Domain\Correspondence\Repositories;

use App\Domain\Correspondence\Models\LetterNumber;
use App\Domain\Correspondence\Models\LetterOut;
use App\Support\Repositories\EloquentRepository;

class CorrespondenceRepository extends EloquentRepository
{
    public function __construct(LetterOut $model)
    {
        parent::__construct($model);
    }

    public function acquireLetterSequence(int $unitId, ?string $prefix, int $year): LetterNumber
    {
        return LetterNumber::firstOrCreate(
            [
                'organization_unit_id' => $unitId,
                'prefix' => $prefix,
                'year' => $year,
            ],
            [
                'format_template' => '{no}/' . ($prefix ? "{$prefix}/" : '') . '{unit}/{bulan}/{tahun}',
                'last_number' => 0,
            ]
        );
    }

    public function findByQrOrNumber(string $identifier): ?LetterOut
    {
        return LetterOut::with(['organizationUnit', 'signedBy'])
            ->where(function ($query) use ($identifier) {
                $query->where('letter_number', $identifier)
                      ->orWhere('qr_code', $identifier);
            })
            ->first();
    }
}
