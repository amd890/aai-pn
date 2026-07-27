<?php

namespace App\Livewire\Admin\Correspondence;

use App\Domain\Correspondence\Models\LetterOut;
use App\Domain\Correspondence\Services\CorrespondenceService;
use App\Domain\Organization\Models\OrganizationUnit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class LetterManager extends Component
{
    use WithPagination;

    public bool $showCreateModal = false;
    public string $recipient = '';
    public string $subject = '';
    public string $content = '';
    public string $type_code = 'UND'; // UND / SK / ST / KET
    public string $noticeMessage = '';

    public function createLetter(CorrespondenceService $service)
    {
        $this->validate([
            'recipient' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type_code' => 'required|string|max:10',
        ]);

        $unit = OrganizationUnit::firstOrCreate(
            ['code' => 'AAI-PUSAT'],
            ['name' => 'Dewan Pengurus Nasional AAI', 'type' => 'pusat']
        );

        $letter = $service->createOutboundLetter(
            $unit,
            $this->recipient,
            $this->subject,
            $this->content,
            Auth::id() ?? 1,
            $this->type_code
        );

        $this->noticeMessage = "Naskah Dinas berhasil diterbitkan dengan nomor resmi: [{$letter->letter_number}] beserta QR otorisasi digital.";
        
        $this->reset(['recipient', 'subject', 'content', 'showCreateModal']);
    }

    public function render()
    {
        $letters = LetterOut::with('organizationUnit')->orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.admin.correspondence.letter-manager', compact('letters'));
    }
}
