<?php

namespace Database\Seeders;

use App\Domain\Auth\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolePermissionSeeder::class);

        // Create Super Admin user
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin AAI',
            'email' => 'superadmin@aai.or.id',
            'username' => 'superadmin',
            'status' => 'active',
        ]);
        $superAdmin->assignRole('super-admin');

        // Create Administrator user
        $admin = User::factory()->create([
            'name' => 'Administrator Portal',
            'email' => 'admin@aai.or.id',
            'username' => 'admin',
            'status' => 'active',
        ]);
        $admin->assignRole('administrator');

        // Call Domain Seeders in dependency order
        $this->call([
            OrganizationSeeder::class,
            MembershipSeeder::class,
            FinanceSeeder::class,
            ContentAndEventSeeder::class,
            MenuSeeder::class,
            LspSeeder::class,
            VotingAndCorrespondenceSeeder::class,
            SettingSeeder::class,
        ]);
    }
}

