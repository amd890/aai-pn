<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\Region;
use App\Livewire\Admin\Membership\VerificationQueue;
use App\Support\Enums\MemberStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminVerificationQueueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_admin_dashboard_requires_authentication()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_verifier_can_approve_pending_member_and_issue_official_kta_number()
    {
        $verifier = User::create(['name' => 'Verifier Anggota', 'email' => 'verifier.kta@aai.id', 'password' => 'secret']);
        $verifier->assignRole('verifier-anggota');

        $region = Region::create(['name' => 'DKI Jakarta', 'code' => '31', 'type' => 'provinsi']);
        
        $calonUser = User::create(['name' => 'Calon Arsiparis Jakarta', 'email' => 'calon.jakarta@aai.id', 'password' => 'secret']);
        $pendingMember = Member::create([
            'user_id' => $calonUser->id,
            'name' => 'Calon Arsiparis Jakarta',
            'nik' => '3173010101900001',
            'region_id' => $region->id,
            'jenjang_arsiparis' => 'Ahli Muda',
            'status' => MemberStatus::Pending,
        ]);

        $this->actingAs($verifier);

        // Visit verification queue page
        $response = $this->get('/admin/members/verification-queue');
        $response->assertStatus(200);
        $response->assertSee('Calon Arsiparis Jakarta', false);

        // Adjust professional rank to Ahli Madya and approve via Livewire
        Livewire::test(VerificationQueue::class)
            ->set("memberLevels.{$pendingMember->id}", 'Ahli Madya')
            ->call('approve', $pendingMember->id)
            ->assertSee("resmi terverifikasi", false);

        $approved = $pendingMember->fresh();
        $this->assertEquals(MemberStatus::Active, $approved->status);
        $this->assertEquals('Ahli Madya', $approved->jenjang_arsiparis);
        $this->assertStringStartsWith('AAI.31.', $approved->member_number);
        $this->assertNotNull($approved->card);
        $this->assertDatabaseHas('dues', ['member_id' => $approved->id]);
    }
}
