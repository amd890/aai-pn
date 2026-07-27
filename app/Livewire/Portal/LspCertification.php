<?php

namespace App\Livewire\Portal;

use App\Domain\LSP\Models\CertificationBatch;
use App\Domain\LSP\Models\CertificationParticipant;
use App\Domain\LSP\Services\LspService;
use App\Domain\Membership\Models\Member;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.portal')]
class LspCertification extends Component
{
    public $member;
    public string $noticeMessage = '';

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->member = Member::where('user_id', $user->id)->first();
        }
    }

    public function registerForBatch(int $batchId, LspService $lspService)
    {
        if (!$this->member) return;
        
        try {
            $batch = CertificationBatch::findOrFail($batchId);
            $lspService->registerParticipant($batch, $this->member);
            $this->noticeMessage = 'Pendaftaran Batch Sertifikasi berhasil! Silakan unggah Portofolio (APL-01/02) Anda pada hari asesmen.';
        } catch (\Exception $e) {
            $this->noticeMessage = 'Gagal: ' . $e->getMessage();
        }
    }

    public function render()
    {
        $openBatches = CertificationBatch::with(['scheme', 'tuk'])
            ->where('status', 'open')
            ->where('scheduled_date', '>=', now())
            ->get();
            
        $myRegistrations = collect();
        if ($this->member) {
            $myRegistrations = CertificationParticipant::with(['batch.scheme', 'certificate'])
                ->where('member_id', $this->member->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('livewire.portal.lsp-certification', [
            'openBatches' => $openBatches,
            'myRegistrations' => $myRegistrations,
        ]);
    }
}
