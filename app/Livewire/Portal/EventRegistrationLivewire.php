<?php

namespace App\Livewire\Portal;

use App\Domain\Event\Models\Event;
use App\Domain\Event\Models\EventRegistration;
use App\Domain\Membership\Models\Member;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.portal')]
class EventRegistrationLivewire extends Component
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

    public function registerForEvent(int $eventId)
    {
        if (!$this->member) return;

        $event = Event::findOrFail($eventId);
        
        if (!$event->isRegistrationOpen()) {
            $this->noticeMessage = 'Pendaftaran ditutup atau kuota penuh.';
            return;
        }

        // Prevent double registration
        $existing = EventRegistration::where('event_id', $eventId)->where('member_id', $this->member->id)->first();
        if ($existing) {
            $this->noticeMessage = 'Anda sudah terdaftar di event ini.';
            return;
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'member_id' => $this->member->id,
            'status' => 'confirmed',
            'registered_at' => now(),
            'confirmed_at' => now(), // Automatically confirm for MVP
        ]);

        $this->noticeMessage = 'Pendaftaran berhasil! Tiket digital Anda telah diterbitkan.';
    }

    public function render()
    {
        $upcomingEvents = Event::published()
            ->upcoming()
            ->orderBy('event_start', 'asc')
            ->get();
            
        $myTickets = collect();
        if ($this->member) {
            $myTickets = EventRegistration::with('event')
                ->where('member_id', $this->member->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('livewire.portal.event-registration', [
            'upcomingEvents' => $upcomingEvents,
            'myTickets' => $myTickets,
        ]);
    }
}
