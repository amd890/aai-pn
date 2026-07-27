<?php

namespace App\Livewire\Admin\Membership;

use App\Domain\Membership\Models\Member;
use App\Domain\Membership\Services\MembershipService;
use App\Support\Enums\MemberStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class VerificationQueue extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedStatus = 'pending';
    public string $noticeMessage = '';

    // Modifiable rank for approval
    public array $memberLevels = [];

    public function mount()
    {
        $pending = Member::where('status', 'pending')->get();
        foreach ($pending as $m) {
            $this->memberLevels[$m->id] = $m->jenjang_arsiparis ?? 'Ahli Muda';
        }
    }

    public function approve(int $memberId, MembershipService $membershipService)
    {
        $member = Member::with('user', 'region')->findOrFail($memberId);

        if ($member->status === MemberStatus::Pending) {
            // Check if verifier adjusted professional rank before approving
            if (isset($this->memberLevels[$memberId]) && $this->memberLevels[$memberId] !== $member->jenjang_arsiparis) {
                $member->update([
                    'jenjang_arsiparis' => $this->memberLevels[$memberId],
                    'position' => 'Arsiparis ' . $this->memberLevels[$memberId],
                ]);
            }

            $approved = $membershipService->approveMember($member, Auth::user());
            $this->noticeMessage = "Sukses! Anggota [{$approved->name}] resmi terverifikasi dengan nomor KTA [{$approved->member_number}].";
        }
    }

    public function render()
    {
        $members = Member::with(['user', 'region', 'institution', 'card'])
            ->when($this->selectedStatus !== 'all', function ($query) {
                $query->where('status', $this->selectedStatus);
            })
            ->when(!empty($this->search), function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('member_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.membership.verification-queue', compact('members'));
    }
}
