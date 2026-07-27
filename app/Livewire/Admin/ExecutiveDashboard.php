<?php

namespace App\Livewire\Admin;

use App\Domain\Correspondence\Models\LetterOut;
use App\Domain\Finance\Models\Payment;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\Region;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ExecutiveDashboard extends Component
{
    public int $totalActiveMembers = 0;
    public int $totalPendingMembers = 0;
    public float $totalRevenue = 0;
    public int $totalLetters = 0;
    public $regions;
    public $recentMembers;

    public function mount()
    {
        $this->totalActiveMembers = Member::where('status', 'active')->count();
        $this->totalPendingMembers = Member::where('status', 'pending')->count();
        $this->totalRevenue = Payment::sum('amount') ?? 0;
        $this->totalLetters = LetterOut::count() ?? 0;
        
        $this->regions = Region::withCount(['members' => function($query) {
            $query->where('status', 'active');
        }])->orderBy('members_count', 'desc')->take(6)->get();

        $this->recentMembers = Member::with(['region', 'institution'])->orderBy('created_at', 'desc')->take(5)->get();
    }

    public function render()
    {
        return view('livewire.admin.executive-dashboard');
    }
}
