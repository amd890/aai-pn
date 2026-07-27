<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RbacAndRoleAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_permission_seeder_creates_expected_roles_and_permissions(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $this->assertDatabaseHas('roles', ['name' => 'super-admin']);
        $this->assertDatabaseHas('roles', ['name' => 'ketua-umum']);
        $this->assertDatabaseHas('roles', ['name' => 'sekretariat-nasional']);
        $this->assertDatabaseHas('roles', ['name' => 'anggota']);

        $this->assertGreaterThan(50, Permission::count(), 'Should seed comprehensive granular permissions');
    }

    public function test_user_can_be_assigned_roles_and_permissions(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create([
            'name' => 'Dr. Arsiparis Test',
            'email' => 'arsip@aai.or.id',
            'username' => 'arsip.test',
            'status' => 'active',
        ]);

        $user->assignRole('ketua-umum');

        $this->assertTrue($user->hasRole('ketua-umum'));
        $this->assertTrue($user->hasPermissionTo('organization.units.view'));
        $this->assertFalse($user->hasRole('super-admin'));
    }

    public function test_super_admin_bypasses_all_permission_checks(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $superAdmin = User::factory()->create([
            'name' => 'Super Admin Test',
            'username' => 'supertest',
        ]);
        $superAdmin->assignRole('super-admin');

        $this->actingAs($superAdmin);

        // Even for arbitrary or restricted permissions, Gate before rule allows super-admin
        $this->assertTrue(Gate::allows('any.random.permission.that.does.not.exist'));
        $this->assertTrue($superAdmin->can('finance.payments.verify'));
    }
}
