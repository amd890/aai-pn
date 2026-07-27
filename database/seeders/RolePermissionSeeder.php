<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Create Permissions ─────────────────────────────────

        $permissions = [
            // Members
            'members.view', 'members.create', 'members.edit', 'members.delete',
            'members.approve', 'members.reject', 'members.export', 'members.import',
            'members.view-own',

            // Member Cards
            'member-cards.view', 'member-cards.generate', 'member-cards.revoke',

            // Member Certificates
            'member-certificates.view', 'member-certificates.generate',

            // Organization
            'organization.units.view', 'organization.units.create', 'organization.units.edit', 'organization.units.delete',
            'organization.periods.view', 'organization.periods.create', 'organization.periods.edit',
            'organization.members.view', 'organization.members.assign',
            'organization.regions.view', 'organization.regions.manage',

            // Finance
            'finance.dues.view', 'finance.dues.create', 'finance.dues.edit', 'finance.dues.delete',
            'finance.dues.view-own',
            'finance.payments.view', 'finance.payments.create', 'finance.payments.verify', 'finance.payments.reject',
            'finance.payments.view-own',
            'finance.invoices.view', 'finance.invoices.generate',
            'finance.reports.view',

            // CMS
            'cms.articles.view', 'cms.articles.create', 'cms.articles.edit', 'cms.articles.delete', 'cms.articles.publish',
            'cms.news.view', 'cms.news.create', 'cms.news.edit', 'cms.news.delete', 'cms.news.publish',
            'cms.galleries.view', 'cms.galleries.create', 'cms.galleries.edit', 'cms.galleries.delete',
            'cms.agendas.view', 'cms.agendas.create', 'cms.agendas.edit', 'cms.agendas.delete',
            'cms.banners.view', 'cms.banners.create', 'cms.banners.edit', 'cms.banners.delete',
            'cms.faqs.view', 'cms.faqs.create', 'cms.faqs.edit', 'cms.faqs.delete',

            // Events
            'events.view', 'events.create', 'events.edit', 'events.delete',
            'events.registrations.view', 'events.registrations.manage',
            'events.attendance.view', 'events.attendance.manage',
            'events.certificates.view', 'events.certificates.generate',
            'events.register', // member self-register

            // LSP
            'lsp.schemes.view', 'lsp.schemes.create', 'lsp.schemes.edit', 'lsp.schemes.delete',
            'lsp.assessors.view', 'lsp.assessors.create', 'lsp.assessors.edit', 'lsp.assessors.delete',
            'lsp.tuks.view', 'lsp.tuks.create', 'lsp.tuks.edit', 'lsp.tuks.delete',
            'lsp.batches.view', 'lsp.batches.create', 'lsp.batches.edit', 'lsp.batches.delete',
            'lsp.participants.view', 'lsp.participants.manage', 'lsp.participants.assess',
            'lsp.certificates.view', 'lsp.certificates.generate',
            'lsp.view-own',

            // Voting
            'voting.elections.view', 'voting.elections.create', 'voting.elections.edit', 'voting.elections.delete',
            'voting.elections.manage', // open, close, count
            'voting.candidates.view', 'voting.candidates.manage',
            'voting.vote', // member can vote
            'voting.results.view',
            'voting.audit.view',

            // Correspondence
            'correspondence.in.view', 'correspondence.in.create', 'correspondence.in.edit', 'correspondence.in.delete',
            'correspondence.out.view', 'correspondence.out.create', 'correspondence.out.edit', 'correspondence.out.delete',
            'correspondence.out.sign',

            // Users & Roles
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'permissions.view', 'permissions.assign',

            // Logs & Audit
            'logs.activity.view', 'logs.audit.view', 'logs.login.view',

            // Settings
            'settings.view', 'settings.edit',
            'seo.view', 'seo.edit',

            // Reports
            'reports.view', 'reports.export',
            'reports.members', 'reports.finance', 'reports.events', 'reports.lsp',

            // System
            'media.view', 'media.manage',
            'backup.view', 'backup.manage',
            'notifications.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // ── Create Roles & Assign Permissions ──────────────────

        // 1. Super Admin — gets ALL permissions via Gate::before
        $superAdmin = Role::create(['name' => 'super-admin']);

        // 2. Administrator
        $administrator = Role::create(['name' => 'administrator']);
        $administrator->givePermissionTo(
            collect($permissions)->filter(fn($p) => ! str_starts_with($p, 'backup.'))->all()
        );

        // 3. Sekretariat Nasional
        $sekretariatNasional = Role::create(['name' => 'sekretariat-nasional']);
        $sekretariatNasional->givePermissionTo([
            'members.view', 'members.create', 'members.edit', 'members.delete',
            'members.approve', 'members.reject', 'members.export', 'members.import',
            'member-cards.view', 'member-cards.generate',
            'member-certificates.view', 'member-certificates.generate',
            'organization.units.view', 'organization.periods.view', 'organization.members.view',
            'organization.regions.view',
            'finance.dues.view', 'finance.payments.view',
            'cms.articles.view', 'cms.articles.create', 'cms.articles.edit', 'cms.articles.delete', 'cms.articles.publish',
            'cms.news.view', 'cms.news.create', 'cms.news.edit', 'cms.news.delete', 'cms.news.publish',
            'cms.galleries.view', 'cms.galleries.create', 'cms.galleries.edit', 'cms.galleries.delete',
            'cms.agendas.view', 'cms.agendas.create', 'cms.agendas.edit', 'cms.agendas.delete',
            'cms.banners.view', 'cms.banners.create', 'cms.banners.edit', 'cms.banners.delete',
            'cms.faqs.view', 'cms.faqs.create', 'cms.faqs.edit', 'cms.faqs.delete',
            'events.view', 'events.create', 'events.edit', 'events.delete',
            'events.registrations.view', 'events.registrations.manage',
            'events.attendance.view', 'events.attendance.manage',
            'events.certificates.view', 'events.certificates.generate',
            'voting.elections.view', 'voting.elections.create', 'voting.elections.edit',
            'voting.elections.manage', 'voting.candidates.view', 'voting.candidates.manage',
            'voting.results.view', 'voting.audit.view',
            'correspondence.in.view', 'correspondence.in.create', 'correspondence.in.edit',
            'correspondence.out.view', 'correspondence.out.create', 'correspondence.out.edit',
            'logs.activity.view', 'logs.audit.view',
            'reports.view', 'reports.export', 'reports.members', 'reports.events',
            'media.view', 'media.manage',
            'notifications.manage',
        ]);

        // 4. Bendahara Nasional
        $bendaharaNasional = Role::create(['name' => 'bendahara-nasional']);
        $bendaharaNasional->givePermissionTo([
            'members.view',
            'finance.dues.view', 'finance.dues.create', 'finance.dues.edit', 'finance.dues.delete',
            'finance.payments.view', 'finance.payments.create', 'finance.payments.verify', 'finance.payments.reject',
            'finance.invoices.view', 'finance.invoices.generate',
            'finance.reports.view',
            'reports.view', 'reports.export', 'reports.finance',
        ]);

        // 5. Ketua Umum
        $ketuaUmum = Role::create(['name' => 'ketua-umum']);
        $ketuaUmum->givePermissionTo([
            'members.view', 'organization.units.view', 'organization.periods.view',
            'organization.members.view', 'finance.dues.view', 'finance.payments.view',
            'events.view', 'lsp.schemes.view', 'lsp.batches.view',
            'voting.elections.view', 'voting.results.view',
            'correspondence.in.view', 'correspondence.out.view', 'correspondence.out.sign',
            'reports.view', 'reports.members', 'reports.finance', 'reports.events', 'reports.lsp',
        ]);

        // 6. Pengurus Pusat
        $pengurusPusat = Role::create(['name' => 'pengurus-pusat']);
        $pengurusPusat->givePermissionTo([
            'members.view', 'organization.units.view', 'organization.periods.view',
            'organization.members.view', 'finance.dues.view',
            'events.view', 'lsp.schemes.view',
            'voting.elections.view', 'voting.results.view',
            'correspondence.in.view', 'correspondence.out.view',
            'correspondence.out.create', 'correspondence.out.edit',
            'reports.view', 'reports.members',
        ]);

        // 7. Pengurus Wilayah (scoped to their region in middleware)
        $pengurusWilayah = Role::create(['name' => 'pengurus-wilayah']);
        $pengurusWilayah->givePermissionTo([
            'members.view', 'members.create', 'members.edit', 'members.approve',
            'members.export',
            'member-cards.view', 'member-cards.generate',
            'organization.units.view', 'organization.periods.view', 'organization.members.view',
            'finance.dues.view', 'finance.payments.view',
            'events.view', 'events.create', 'events.edit',
            'events.registrations.view', 'events.registrations.manage',
            'events.attendance.view', 'events.attendance.manage',
            'voting.elections.view', 'voting.results.view',
            'correspondence.in.view', 'correspondence.in.create',
            'correspondence.out.view', 'correspondence.out.create', 'correspondence.out.edit',
            'reports.view', 'reports.members', 'reports.events',
        ]);

        // 8. Pengurus Cabang (scoped to their branch in middleware)
        $pengurusCabang = Role::create(['name' => 'pengurus-cabang']);
        $pengurusCabang->givePermissionTo([
            'members.view', 'members.create', 'members.edit',
            'members.export',
            'member-cards.view',
            'organization.units.view', 'organization.periods.view', 'organization.members.view',
            'finance.dues.view', 'finance.payments.view',
            'events.view', 'events.create', 'events.edit',
            'events.registrations.view', 'events.registrations.manage',
            'events.attendance.view', 'events.attendance.manage',
            'correspondence.in.view', 'correspondence.in.create',
            'correspondence.out.view', 'correspondence.out.create',
            'reports.view', 'reports.members',
        ]);

        // 9. LSP Admin
        $lspAdmin = Role::create(['name' => 'lsp-admin']);
        $lspAdmin->givePermissionTo([
            'lsp.schemes.view', 'lsp.schemes.create', 'lsp.schemes.edit', 'lsp.schemes.delete',
            'lsp.assessors.view', 'lsp.assessors.create', 'lsp.assessors.edit', 'lsp.assessors.delete',
            'lsp.tuks.view', 'lsp.tuks.create', 'lsp.tuks.edit', 'lsp.tuks.delete',
            'lsp.batches.view', 'lsp.batches.create', 'lsp.batches.edit', 'lsp.batches.delete',
            'lsp.participants.view', 'lsp.participants.manage', 'lsp.participants.assess',
            'lsp.certificates.view', 'lsp.certificates.generate',
            'reports.view', 'reports.lsp',
        ]);

        // 10. Verifier Anggota
        $verifierAnggota = Role::create(['name' => 'verifier-anggota']);
        $verifierAnggota->givePermissionTo([
            'members.view', 'members.approve', 'members.reject',
            'member-cards.view',
        ]);

        // 11. Anggota (Member)
        $anggota = Role::create(['name' => 'anggota']);
        $anggota->givePermissionTo([
            'members.view-own',
            'finance.dues.view-own', 'finance.payments.view-own',
            'events.view', 'events.register',
            'voting.vote', 'voting.elections.view',
            'lsp.view-own',
        ]);

        // 12. Guest (no permissions, public access only)
        Role::create(['name' => 'guest']);
    }
}
