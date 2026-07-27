<?php

namespace App\Domain\Correspondence\Services;

use App\Domain\Correspondence\Models\LetterOut;
use App\Domain\Correspondence\Repositories\CorrespondenceRepository;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Support\Services\BaseService;
use Illuminate\Support\Facades\DB;

class CorrespondenceService extends BaseService
{
    public function __construct(protected CorrespondenceRepository $correspondenceRepository)
    {
    }

    /**
     * Atomically acquire next letter number sequence and produce formatted official document number.
     * Template placeholders: {no}, {prefix}, {unit}, {bulan}, {tahun}
     */
    public function createOutboundLetter(
        OrganizationUnit $unit,
        string $recipient,
        string $subject,
        string $content,
        int $creatorId,
        ?string $prefix = 'UND',
        ?int $signedBy = null,
        ?string $signerPosition = null
    ): LetterOut {
        return $this->transactional(function () use ($unit, $recipient, $subject, $content, $creatorId, $prefix, $signedBy, $signerPosition) {
            $year = (int) date('Y');
            $letterSeq = $this->correspondenceRepository->acquireLetterSequence($unit->id, $prefix, $year);

            // Increment sequence atomically
            $letterSeq->increment('last_number');
            $sequenceNumber = $letterSeq->fresh()->last_number;

            // Format month in Roman numerals for official administration
            $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $currentMonthIndex = ((int) date('n')) - 1;
            $romanMonth = $romanMonths[$currentMonthIndex];

            // Parse format template
            $template = $letterSeq->format_template;
            $formattedNo = str_pad((string) $sequenceNumber, 3, '0', STR_PAD_LEFT);

            $letterNumber = str_replace(
                ['{no}', '{prefix}/', '{prefix}', '{unit}', '{bulan}', '{tahun}'],
                [
                    $formattedNo,
                    $prefix ? "{$prefix}/" : '',
                    $prefix ?? '',
                    $unit->code ?? 'AAI-PUSAT',
                    $romanMonth,
                    (string) $year,
                ],
                $template
            );

            // Clean up any duplicated slashes from optional prefixes
            $letterNumber = preg_replace('#/+#', '/', $letterNumber);
            $letterNumber = trim($letterNumber, '/');

            $qrCode = 'QR-DOC-' . hash('sha256', $letterNumber . now());

            return LetterOut::create([
                'letter_number' => $letterNumber,
                'recipient' => $recipient,
                'subject' => $subject,
                'content' => $content,
                'letter_date' => date('Y-m-d'),
                'organization_unit_id' => $unit->id,
                'signed_by' => $signedBy,
                'signer_position' => $signerPosition ?: ($signedBy ? 'Ketua Umum AAI' : null),
                'qr_code' => $qrCode,
                'status' => $signedBy ? 'signed' : 'draft',
                'created_by' => $creatorId,
            ]);
        }, 'Failed to create outbound letter');
    }
}
