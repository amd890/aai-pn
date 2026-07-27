<?php

namespace App\Livewire\Portal;

use App\Domain\Membership\Models\Member;
use App\Domain\Voting\Models\Election;
use App\Domain\Voting\Models\ElectionCandidate;
use App\Domain\Voting\Services\VotingService;
use App\Support\Enums\ElectionStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.portal')]
class ElectionVoting extends Component
{
    public $member;
    public string $noticeMessage = '';
    public string $errorMessage = '';

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->member = Member::where('user_id', $user->id)->first();
        }
    }

    public function castVote(int $electionId, int $candidateId, VotingService $votingService)
    {
        $this->errorMessage = '';
        $this->noticeMessage = '';

        if (!$this->member) {
            $this->errorMessage = 'Data anggota tidak ditemukan. Anda tidak berhak memilih.';
            return;
        }

        try {
            $election = Election::findOrFail($electionId);
            $candidate = ElectionCandidate::findOrFail($candidateId);

            $votingService->castVote(
                $election,
                $this->member,
                $candidate,
                request()->ip() ?? '127.0.0.1'
            );

            $this->noticeMessage = 'Suara rahasia Anda telah berhasil direkam secara kriptografis! Terima kasih atas partisipasi Anda.';
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        // Get active elections that are Open and currently running
        $elections = Election::with(['candidates.member'])
            ->where('status', ElectionStatus::Open)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->get();

        return view('livewire.portal.election-voting', [
            'elections' => $elections
        ]);
    }
}
