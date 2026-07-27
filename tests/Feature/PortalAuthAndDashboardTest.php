<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Finance\Models\Due;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\Region;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Portal\MemberDashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PortalAuthAndDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_register_and_login_portal_pages_render()
    {
        $this->get('/login')->assertStatus(200)->assertSee('Portal Masuk', false);
        $this->get('/register')->assertStatus(200)->assertSee('Registrasi Keanggotaan', false);
    }

    public function test_reactive_registration_flow_creates_pending_member_and_user()
    {
        $region = Region::create(['name' => 'Jawa Timur', 'code' => '35', 'type' => 'provinsi']);

        Livewire::test(Register::class)
            ->set('name', 'Andi Arsiparis, M.AP.')
            ->set('email', 'andi@jatim.id')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('nik', '3573010101850001')
            ->set('nip', '198501012010011002')
            ->set('phone', '081234123412')
            ->set('region_id', $region->id)
            ->set('jenjang_arsiparis', 'Ahli Madya')
            ->set('golongan', 'IV/a')
            ->call('register')
            ->assertSet('registered', true);

        $this->assertDatabaseHas('users', ['email' => 'andi@jatim.id']);
        $this->assertDatabaseHas('members', ['nik' => '3573010101850001', 'status' => 'pending']);
    }

    public function test_reactive_login_redirects_admin_to_admin_and_member_to_portal()
    {
        $admin = User::create(['name' => 'Admin Boss', 'email' => 'boss@aai.id', 'password' => bcrypt('secret')]);
        $admin->assignRole('super-admin');

        Livewire::test(Login::class)
            ->set('email', 'boss@aai.id')
            ->set('password', 'secret')
            ->call('login')
            ->assertRedirect(route('admin.dashboard'));

        $memberUser = User::create(['name' => 'Regular Member', 'email' => 'member@aai.id', 'password' => bcrypt('secret')]);
        
        Livewire::test(Login::class)
            ->set('email', 'member@aai.id')
            ->set('password', 'secret')
            ->call('login')
            ->assertRedirect(route('portal.dashboard'));
    }

    public function test_member_dashboard_displays_digital_kta_and_can_simulate_due_payment()
    {
        $user = User::create(['name' => 'Active Member User', 'email' => 'active.user@aai.id', 'password' => 'secret']);
        $member = Member::create([
            'user_id' => $user->id,
            'name' => 'Active Member User',
            'nik' => '3273010101800001',
            'member_number' => 'AAI.32.2026.0001',
            'status' => 'active',
            'jenjang_arsiparis' => 'Ahli Utama'
        ]);

        $due = Due::create([
            'member_id' => $member->id,
            'period_year' => 2026,
            'amount' => 150000,
            'status' => 'pending',
            'due_date' => now()->addDays(30)
        ]);

        $this->actingAs($user);

        // Visit Portal Dashboard via HTTP
        $response = $this->get('/portal/dashboard');
        $response->assertStatus(200);
        $response->assertSee('AAI.32.2026.0001');
        $response->assertSee('Iuran Wajib AAI Tahun 2026');

        // Simulate VA payment in Livewire component
        Livewire::test(MemberDashboard::class)
            ->set('selectedMethod', 'virtual_account')
            ->call('simulatePayment', $due->id)
            ->assertSee('Pembayaran VA Berhasil Dilunasi!', false);

        $this->assertEquals('paid', $due->fresh()->status->value);
        $this->assertDatabaseHas('payments', ['payable_id' => $due->id, 'status' => 'verified', 'method' => 'gateway']);
    }
}
