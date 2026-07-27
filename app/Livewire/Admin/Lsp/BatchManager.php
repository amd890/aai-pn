<?php

namespace App\Livewire\Admin\Lsp;

use App\Domain\LSP\Models\CertificationBatch;
use App\Domain\LSP\Models\CertificationParticipant;
use App\Domain\LSP\Services\LspService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class BatchManager extends Component
{
    public bool $showParticipantModal = false;
    public ?int $activeBatchId = null;
    public string $noticeMessage = '';

    public function manageParticipants(int $batchId)
    {
        $this->activeBatchId = $batchId;
        $this->showParticipantModal = true;
    }

    public function passParticipant(int $participantId, LspService $lspService)
    {
        $participant = CertificationParticipant::with('batch.scheme')->findOrFail($participantId);
        
        if ($participant->status->value !== 'competent') {
            $lspService->issueCertificate($participant, 'Lulus Kompeten');
            $this->noticeMessage = 'Sertifikat BNSP/LSP berhasil diterbitkan untuk peserta ini!';
        }
    }

    public function failParticipant(int $participantId)
    {
        $participant = CertificationParticipant::findOrFail($participantId);
        $participant->update([
            'status' => 'not_competent',
            'assessment_date' => now(),
            'result' => 'Belum Kompeten'
        ]);
        $this->noticeMessage = 'Status peserta diperbarui menjadi Belum Kompeten.';
    }

    public function render()
    {
        $batches = CertificationBatch::with(['scheme', 'assessor', 'tuk'])
            ->withCount('participants')
            ->orderBy('scheduled_date', 'desc')
            ->get();

        $activeParticipants = [];
        if ($this->activeBatchId) {
            $activeParticipants = CertificationParticipant::with(['member', 'certificate'])
                ->where('batch_id', $this->activeBatchId)
                ->get();
        }

        return view('livewire.admin.lsp.batch-manager', [
            'batches' => $batches,
            'activeParticipants' => $activeParticipants,
        ]);
    }
}
