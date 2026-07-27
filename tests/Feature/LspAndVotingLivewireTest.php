<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\LSP\Models\CertificationBatch;
use App\Domain\LSP\Models\CertificationParticipant;
use App\Domain\LSP\Models\CertificationScheme;
use App\Domain\Membership\Models\Member;
use App\Domain\Voting\Models\Election;
use App\Domain\Voting\Models\ElectionCandidate;
use App\Domain\Voting\Models\ElectionVote;
use App\Livewire\Admin\Lsp\BatchManager;
use App\Livewire\Admin\Voting\ElectionManager;
use App\Livewire\Portal\ElectionVoting;
use App\Livewire\Portal\LspCertification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LspAndVotingLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_admin_can_manage_election_and_member_can_vote()
    {
        // Setup Users
        $adminUser = User::create(['name' => 'Admin KPU', 'email' => 'kpu@aai.id', 'password' => 'secret']);
        $voterUser = User::create(['name' => 'Voter', 'email' => 'voter@aai.id', 'password' => 'secret']);
        $voter = Member::create(['user_id' => $voterUser->id, 'name' => 'Voter Member', 'nik' => '111', 'status' => 'active']);
        $candidateMember = Member::create(['user_id' => $adminUser->id, 'name' => 'Candidate Member', 'nik' => '222', 'status' => 'active']);

        $this->actingAs($adminUser);

        // 1. Create Election via Livewire
        Livewire::test(ElectionManager::class)
            ->set('title', 'Pemilihan Nasional 2026')
            ->set('level', 'nasional')
            ->set('status', 'open')
            ->set('start_at', now()->subDay()->format('Y-m-d\TH:i'))
            ->set('end_at', now()->addDays(2)->format('Y-m-d\TH:i'))
            ->call('saveElection')
            ->assertSee('Pemilihan berhasil dibuat');

        $election = Election::first();
        $this->assertNotNull($election);
        $this->assertTrue($election->isOpen());

        // 2. Add Candidate via Livewire
        Livewire::test(ElectionManager::class)
            ->call('manageCandidates', $election->id)
            ->set('candidateMemberId', $candidateMember->id)
            ->set('candidateNumber', 1)
            ->set('visionMission', 'Visi Misi 1')
            ->call('saveCandidate')
            ->assertSee('Kandidat berhasil ditambahkan');

        $candidate = ElectionCandidate::first();
        $this->assertEquals(1, $candidate->candidate_number);

        // 3. Voter casts vote via Livewire Portal
        $this->actingAs($voterUser);
        
        Livewire::test(ElectionVoting::class)
            ->call('castVote', $election->id, $candidate->id)
            ->assertSee('Suara rahasia Anda telah berhasil direkam');

        // Check if vote is recorded and double-voting is prevented
        $this->assertEquals(1, ElectionVote::count());
        $this->assertEquals(1, $candidate->fresh()->vote_count);

        // Try double voting
        Livewire::test(ElectionVoting::class)
            ->call('castVote', $election->id, $candidate->id)
            ->assertSee('Double voting prevented');
            
        $this->assertEquals(1, ElectionVote::count()); // Still 1
    }

    public function test_member_can_register_for_lsp_and_admin_can_issue_certificate()
    {
        $adminUser = User::create(['name' => 'LSP Admin', 'email' => 'lsp@aai.id', 'password' => 'secret']);
        $memberUser = User::create(['name' => 'Peserta', 'email' => 'peserta@aai.id', 'password' => 'secret']);
        $member = Member::create(['user_id' => $memberUser->id, 'name' => 'Peserta Ujian', 'nik' => '333', 'status' => 'active']);

        $scheme = CertificationScheme::create(['code' => 'ARS-01', 'name' => 'Arsiparis Terampil']);
        $tuk = \App\Domain\LSP\Models\Tuk::create([
            'name' => 'TUK Mandiri ANRI',
            'code' => 'TUK-ANRI-01',
            'type' => 'Mandiri',
            'is_active' => true,
        ]);
        
        $batch = CertificationBatch::create([
            'scheme_id' => $scheme->id,
            'tuk_id' => $tuk->id,
            'batch_number' => 'BATCH-001',
            'status' => 'open',
            'scheduled_date' => now()->addDays(5),
            'end_date' => now()->addDays(7),
        ]);

        // 1. Member registers via Portal
        $this->actingAs($memberUser);
        
        Livewire::test(LspCertification::class)
            ->call('registerForBatch', $batch->id)
            ->assertSee('Pendaftaran Batch Sertifikasi berhasil');

        $participant = CertificationParticipant::first();
        $this->assertNotNull($participant);
        $this->assertEquals('registered', $participant->status->value);

        // 2. Admin issues certificate via Livewire
        $this->actingAs($adminUser);
        
        Livewire::test(BatchManager::class)
            ->call('manageParticipants', $batch->id)
            ->call('passParticipant', $participant->id)
            ->assertSee('Sertifikat BNSP/LSP berhasil diterbitkan');

        $participant->refresh();
        $this->assertEquals('competent', $participant->status->value);
        $this->assertNotNull($participant->certificate);
        $this->assertStringContainsString('BNSP/LSP-AAI', $participant->certificate->certificate_number);
    }
}
