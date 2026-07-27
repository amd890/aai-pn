<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Correspondence\Services\CorrespondenceService;
use App\Domain\Finance\Services\FinanceService;
use App\Domain\Membership\Services\MembershipService;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Domain\Organization\Models\Region;
use App\Domain\Voting\Models\Election;
use App\Domain\Voting\Models\ElectionCandidate;
use App\Domain\Voting\Services\VotingService;
use App\Support\Enums\MemberStatus;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainServicesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_membership_service_registration_and_approval_workflow()
    {
        $membershipService = app(MembershipService::class);
        $region = Region::create(['name' => 'Jawa Barat', 'code' => '32', 'type' => 'provinsi']);
        
        // Register new member
        $member = $membershipService->registerMember(
            ['email' => 'arsiparis@jabar.id', 'name' => 'Budi Arsiparis'],
            ['name' => 'Budi Arsiparis', 'nik' => '3273031234560001', 'nip' => '198501012010011001', 'region_id' => $region->id]
        );

        $this->assertEquals(MemberStatus::Pending, $member->status);
        $this->assertNull($member->member_number);
        $this->assertDatabaseHas('users', ['email' => 'arsiparis@jabar.id']);
        $this->assertDatabaseHas('member_status_histories', ['to_status' => 'pending']);

        // Approve member
        $verifier = User::create(['name' => 'Verifier', 'email' => 'verifier@aai.id', 'password' => 'secret']);
        $approvedMember = $membershipService->approveMember($member, $verifier);

        $this->assertEquals(MemberStatus::Active, $approvedMember->status);
        $this->assertStringStartsWith('AAI.32.', $approvedMember->member_number);
        $this->assertNotNull($approvedMember->card);
        $this->assertStringStartsWith('QR-AAI-', $approvedMember->card->qr_code);
        
        // Ensure finance service automatically generated mandatory initial due
        $this->assertDatabaseHas('dues', ['member_id' => $approvedMember->id, 'status' => 'pending']);
    }

    public function test_correspondence_service_generates_atomic_letter_sequence()
    {
        $service = app(CorrespondenceService::class);
        $user = User::create(['name' => 'Admin DOC', 'email' => 'admin.doc@aai.id', 'password' => 'secret']);
        $unit = OrganizationUnit::create(['name' => 'AAI Pusat', 'type' => 'pusat', 'code' => 'AAI-PUSAT']);

        $letter1 = $service->createOutboundLetter($unit, 'Kepala ANRI', 'Undangan Rakornas', 'Isi Surat', $user->id, 'UND');
        $letter2 = $service->createOutboundLetter($unit, 'Menteri PANRB', 'Undangan Rakornas 2', 'Isi Surat 2', $user->id, 'UND');

        $year = date('Y');
        $this->assertStringContainsString("001/UND/AAI-PUSAT/", $letter1->letter_number);
        $this->assertStringContainsString("002/UND/AAI-PUSAT/", $letter2->letter_number);
        $this->assertStringContainsString((string)$year, $letter1->letter_number);
    }

    public function test_voting_service_casts_anonymous_vote_and_prevents_double_voting()
    {
        $votingService = app(VotingService::class);
        
        $user = User::create(['name' => 'Voter User', 'email' => 'voter@aai.id', 'password' => 'secret']);
        $voter = \App\Domain\Membership\Models\Member::create([
            'user_id' => $user->id,
            'name' => 'Voter 1',
            'nik' => '3200001112223333',
            'status' => MemberStatus::Active,
        ]);

        $unit = OrganizationUnit::create(['name' => 'AAI Pusat Voting', 'type' => 'pusat', 'code' => 'VOTE']);
        $election = Election::create([
            'title' => 'Pemilihan Ketum 2024',
            'level' => 'nasional',
            'type' => 'pemilihan',
            'organization_unit_id' => $unit->id,
            'start_at' => now(),
            'end_at' => now()->addDays(2),
            'status' => 'open',
            'created_by' => $user->id
        ]);

        $candidate = ElectionCandidate::create([
            'election_id' => $election->id,
            'member_id' => $voter->id,
            'candidate_number' => 1,
            'vision_mission' => 'Visi Misi Tes',
            'status' => 'approved',
            'vote_count' => 0
        ]);

        // Cast vote
        $vote = $votingService->castVote($election, $voter, $candidate);

        $this->assertDatabaseHas('election_votes', ['id' => $vote->id, 'candidate_id' => $candidate->id]);
        $this->assertEquals(1, $candidate->fresh()->vote_count);
        $this->assertNotEmpty($vote->voter_hash);

        // Attempt double voting
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Member has already cast a vote in this election');
        $votingService->castVote($election, $voter, $candidate);
    }

    public function test_membership_privacy_data_masking()
    {
        $service = app(MembershipService::class);
        $maskedNik = $service->maskPersonalData('3273031234560001', 4, 4);
        
        $this->assertEquals('3273XXXXXXXX0001', $maskedNik);
        $this->assertStringNotContainsString('03123456', $maskedNik);
    }
}
