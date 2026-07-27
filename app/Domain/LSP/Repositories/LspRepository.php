<?php

namespace App\Domain\LSP\Repositories;

use App\Domain\LSP\Models\CertificationBatch;
use App\Domain\LSP\Models\LspCertificate;
use App\Support\Repositories\EloquentRepository;

class LspRepository extends EloquentRepository
{
    public function __construct(LspCertificate $model)
    {
        parent::__construct($model);
    }

    public function findByCertificateNumberOrQr(string $identifier): ?LspCertificate
    {
        return LspCertificate::with(['participant.member.institution', 'scheme'])
            ->where(function ($query) use ($identifier) {
                $query->where('certificate_number', $identifier)
                      ->orWhere('qr_code', $identifier);
            })
            ->first();
    }

    public function getOpenBatches()
    {
        return CertificationBatch::with(['scheme', 'tuk'])
            ->where('status', 'open')
            ->orderBy('scheduled_date', 'asc')
            ->get();
    }
}
