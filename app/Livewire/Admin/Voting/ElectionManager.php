<?php

namespace App\Livewire\Admin\Voting;

use App\Domain\Voting\Models\Election;
use App\Domain\Voting\Models\ElectionCandidate;
use App\Support\Enums\ElectionLevel;
use App\Support\Enums\ElectionStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ElectionManager extends Component
{
    public bool $showElectionModal = false;
    public bool $showCandidateModal = false;
    public ?int $activeElectionId = null;

    public string $title = '';
    public string $description = '';
    public string $level = 'nasional';
    public string $status = 'draft';
    public $start_at;
    public $end_at;

    public string $candidateMemberId = '';
    public string $candidateNumber = '';
    public string $visionMission = '';
    public string $profileSummary = '';

    public string $noticeMessage = '';

    public function createElection()
    {
        $this->reset(['title', 'description', 'level', 'status', 'start_at', 'end_at']);
        $this->showElectionModal = true;
    }

    public function saveElection()
    {
        $this->validate([
            'title' => 'required|string',
            'level' => 'required|string',
            'status' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        Election::create([
            'title' => $this->title,
            'description' => $this->description,
            'level' => ElectionLevel::tryFrom($this->level) ?? ElectionLevel::Nasional,
            'status' => ElectionStatus::tryFrom($this->status) ?? ElectionStatus::Draft,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'created_by' => auth()->id() ?? 1,
            'max_vote' => 1,
        ]);

        $this->showElectionModal = false;
        $this->noticeMessage = 'Pemilihan berhasil dibuat.';
    }

    public function manageCandidates(int $electionId)
    {
        $this->activeElectionId = $electionId;
        $this->showCandidateModal = true;
    }

    public function saveCandidate()
    {
        $this->validate([
            'candidateMemberId' => 'required|integer',
            'candidateNumber' => 'required|integer',
            'visionMission' => 'required|string',
        ]);

        ElectionCandidate::create([
            'election_id' => $this->activeElectionId,
            'member_id' => $this->candidateMemberId,
            'candidate_number' => $this->candidateNumber,
            'vision_mission' => $this->visionMission,
            'profile_summary' => $this->profileSummary,
            'status' => 'active',
            'vote_count' => 0,
        ]);

        $this->reset(['candidateMemberId', 'candidateNumber', 'visionMission', 'profileSummary']);
        $this->noticeMessage = 'Kandidat berhasil ditambahkan.';
    }

    public function render()
    {
        $elections = Election::withCount('votes')
            ->orderBy('created_at', 'desc')
            ->get();

        $activeCandidates = [];
        if ($this->activeElectionId) {
            $activeCandidates = ElectionCandidate::with('member')
                ->where('election_id', $this->activeElectionId)
                ->orderBy('candidate_number')
                ->get();
        }

        return view('livewire.admin.voting.election-manager', [
            'elections' => $elections,
            'activeCandidates' => $activeCandidates,
        ]);
    }
}
