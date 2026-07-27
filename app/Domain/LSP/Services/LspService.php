<?php

namespace App\Domain\LSP\Services;

use App\Domain\LSP\Models\CertificationBatch;
use App\Domain\LSP\Models\CertificationParticipant;
use App\Domain\LSP\Models\LspCertificate;
use App\Domain\Membership\Models\Member;
use App\Support\Enums\CertificationParticipantStatus;
use App\Support\Services\BaseService;
use Exception;

class LspService extends BaseService
{
    /**
     * Register a member into an active competency certification batch.
     *
     * @throws Exception
     */
    public function registerParticipant(CertificationBatch $batch, Member $member): CertificationParticipant
    {
        return $this->transactional(function () use ($batch, $member) {
            if ($batch->status !== 'open') {
                throw new Exception('Certification batch is closed for registration.');
            }

            $existingCount = CertificationParticipant::where('batch_id', $batch->id)->count();
            if ($batch->quota && $existingCount >= $batch->quota) {
                throw new Exception('Certification batch quota has been fully exhausted.');
            }

            return CertificationParticipant::create([
                'batch_id' => $batch->id,
                'member_id' => $member->id,
                'status' => CertificationParticipantStatus::Registered,
            ]);
        }, 'Failed to register participant to batch');
    }

    /**
     * Issue official BNSP/LSP Competency Certificate upon passing assessment.
     */
    public function issueCertificate(
        CertificationParticipant $participant,
        string $result = 'Lulus Kompeten',
        ?string $customCertNumber = null
    ): LspCertificate {
        return $this->transactional(function () use ($participant, $result, $customCertNumber) {
            $participant->update([
                'status' => CertificationParticipantStatus::Competent,
                'assessment_date' => now(),
                'result' => $result,
            ]);

            $batch = $participant->batch;
            $scheme = $batch->scheme;
            $year = date('Y');

            if (!$customCertNumber) {
                $count = LspCertificate::whereYear('issued_at', $year)->count() + 1;
                $formattedNo = str_pad((string) $count, 5, '0', STR_PAD_LEFT);
                $customCertNumber = "BNSP/LSP-AAI/{$scheme->code}/{$year}/{$formattedNo}";
            }

            $qrCode = 'QR-BNSP-' . md5($customCertNumber . $participant->id . now());

            return LspCertificate::create([
                'participant_id' => $participant->id,
                'scheme_id' => $scheme->id,
                'certificate_number' => $customCertNumber,
                'qr_code' => $qrCode,
                'issued_at' => now(),
                'expired_at' => now()->addYears(3),
                'status' => 'active',
            ]);
        }, 'Failed to issue LSP certificate');
    }
}
