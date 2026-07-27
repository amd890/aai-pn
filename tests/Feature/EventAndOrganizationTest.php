<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Event\Models\Event;
use App\Domain\Event\Models\EventRegistration;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Livewire\Admin\Events\EventManager;
use App\Livewire\Admin\Organization\UnitManager;
use App\Livewire\Admin\System\AuditLogViewer;
use App\Livewire\Portal\EventRegistrationLivewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class EventAndOrganizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_admin_can_manage_events_and_members_can_register()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@aai.id', 'password' => 'secret']);
        $memberUser = User::create(['name' => 'Member', 'email' => 'member@aai.id', 'password' => 'secret']);
        $member = Member::create(['user_id' => $memberUser->id, 'name' => 'Peserta Diklat', 'status' => 'active']);

        $this->actingAs($admin);

        // 1. Admin creates an event via Livewire
        Livewire::test(EventManager::class)
            ->set('title', 'Diklat Kearsipan Digital 2026')
            ->set('description', 'Bimtek Kearsipan Digital dan Sistem Persuratan')
            ->set('format', 'online')
            ->set('event_start', now()->addDays(5)->format('Y-m-d\TH:i'))
            ->set('event_end', now()->addDays(6)->format('Y-m-d\TH:i'))
            ->set('registration_start', now()->subDay()->format('Y-m-d\TH:i'))
            ->set('registration_end', now()->addDays(4)->format('Y-m-d\TH:i'))
            ->set('quota', 50)
            ->set('is_free', true)
            ->set('status', 'published')
            ->call('save')
            ->assertSee('Event baru berhasil dibuat');

        $event = Event::first();
        $this->assertNotNull($event);
        $this->assertEquals('Diklat Kearsipan Digital 2026', $event->title);

        // 2. Member registers via Portal
        $this->actingAs($memberUser);

        Livewire::test(EventRegistrationLivewire::class)
            ->call('registerForEvent', $event->id)
            ->assertSee('Pendaftaran berhasil');

        $registration = EventRegistration::first();
        $this->assertNotNull($registration);
        $this->assertEquals($member->id, $registration->member_id);
        
        // 3. Prevent double registration
        Livewire::test(EventRegistrationLivewire::class)
            ->call('registerForEvent', $event->id)
            ->assertSee('Anda sudah terdaftar');
            
        $this->assertEquals(1, EventRegistration::count());
    }

    public function test_admin_can_manage_organization_units()
    {
        $admin = User::create(['name' => 'Admin Org', 'email' => 'org@aai.id', 'password' => 'secret']);
        $this->actingAs($admin);

        Livewire::test(UnitManager::class)
            ->set('name', 'Pengurus Wilayah Jawa Barat')
            ->set('type', 'wilayah')
            ->set('code', 'PW-JABAR')
            ->set('email', 'jabar@aai.id')
            ->call('save')
            ->assertSee('berhasil ditambahkan');

        $unit = OrganizationUnit::first();
        $this->assertEquals('Pengurus Wilayah Jawa Barat', $unit->name);
        $this->assertEquals('PW-JABAR', $unit->code);
    }
    
    public function test_super_admin_can_view_audit_logs()
    {
        $admin = User::create(['name' => 'Super Admin', 'email' => 'super@aai.id', 'password' => 'secret']);
        $this->actingAs($admin);
        
        // Create a fake activity log
        activity('system')
            ->causedBy($admin)
            ->log('Test system setup');
            
        Livewire::test(AuditLogViewer::class)
            ->assertSee('Test system setup')
            ->assertSee('Super Admin');
    }
}
