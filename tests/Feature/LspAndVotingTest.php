<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\LSP\Models\CertificationBatch;
use App\Domain\LSP\Models\CertificationParticipant;
use App\Domain\LSP\Models\CertificationScheme;
use App\Domain\LSP\Models\LspCertificate;
use App\Domain\LSP\Models\Tuk;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Domain\Voting\Models\Election;
use App\Domain\Voting\Models\ElectionCandidate;
use App\Domain\Voting\Models\ElectionVote;
use App\Support\Enums\CertificationParticipantStatus;
use App\Support\Enums\ElectionLevel;
use App\Support\Enums\ElectionStatus;
use App\Support\Enums\OrganizationUnitType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LspAndVotingTest extends TestCase
{
    use RefreshDatabase;

    public function test_lsp_batch_participant_assessment_and_certificate_issuance(): void
    {
        $user = User::factory()->create();
        $member = Member::create(['user_id' => $user->id, 'name' => 'Peserta LSP']);

        $scheme = CertificationScheme::create([
            'name' => 'Skema Arsiparis Ahli Muda',
            'code' => 'SKM-AHLI-MUDA',
            'is_active' => true,
        ]);

        $tuk = Tuk::create([
            'name' => 'TUK Mandiri ANRI',
            'code' => 'TUK-ANRI-01',
            'type' => 'Mandiri',
            'is_active' => true,
        ]);

        $batch = CertificationBatch::create([
            'scheme_id' => $scheme->id,
            'tuk_id' => $tuk->id,
            'batch_number' => 'BATCH-I-2024',
            'scheduled_date' => now()->addWeeks(2),
            'quota' => 30,
            'status' => 'open',
        ]);

        $participant = CertificationParticipant::create([
            'batch_id' => $batch->id,
            'member_id' => $member->id,
            'status' => CertificationParticipantStatus::Competent,
            'assessment_date' => now(),
            'result' => 'Lulus Kompeten',
        ]);

        $certificate = LspCertificate::create([
            'participant_id' => $participant->id,
            'scheme_id' => $scheme->id,
            'certificate_number' => 'CERT/LSP/001',
            'qr_code' => 'QR-LSP-001',
            'issued_at' => now(),
            'expired_at' => now()->addYears(3),
            'status' => 'active',
        ]);

        $this->assertTrue($certificate->isActive());
        $this->assertSame('BATCH-I-2024', $participant->batch->batch_number);
        $this->assertSame('Skema Arsiparis Ahli Muda', $certificate->scheme->name);
    }

    public function test_election_creation_candidate_registration_and_voting(): void
    {
        $pusat = OrganizationUnit::create([
            'name' => 'Pusat AAI',
            'type' => OrganizationUnitType::Pusat,
            'code' => 'AAI-PUSAT',
        ]);

        $election = Election::create([
            'title' => 'Pemilihan Ketum AAI 2024',
            'level' => ElectionLevel::Nasional,
            'type' => 'Ketum',
            'organization_unit_id' => $pusat->id,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDays(2),
            'status' => ElectionStatus::Open,
        ]);

        $this->assertTrue($election->isOpen());

        $candidateUser = User::factory()->create();
        $candidateMember = Member::create(['user_id' => $candidateUser->id, 'name' => 'Calon 1']);

        $candidate = ElectionCandidate::create([
            'election_id' => $election->id,
            'member_id' => $candidateMember->id,
            'candidate_number' => 1,
            'vision_mission' => 'Visi Misi Calon 1',
            'status' => 'active',
        ]);

        $voterUser = User::factory()->create();
        $voterMember = Member::create(['user_id' => $voterUser->id, 'name' => 'Voter 1']);

        ElectionVote::create([
            'election_id' => $election->id,
            'candidate_id' => $candidate->id,
            'voter_hash' => hash('sha256', 'voter_' . $voterMember->id),
            'voted_at' => now(),
            'ip_address' => '127.0.0.1',
        ]);

        $this->assertCount(1, $election->candidates);
        $this->assertCount(1, $election->votes);
        $this->assertSame('Calon 1', $election->candidates->first()->member->name);
    }
}
