<?php

namespace Database\Seeders;

use App\Domain\Auth\Models\User;
use App\Domain\Membership\Models\Member;
use App\Domain\Membership\Models\MemberCard;
use App\Domain\Organization\Models\Institution;
use App\Domain\Organization\Models\OrganizationMember;
use App\Domain\Organization\Models\OrganizationPeriod;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Domain\Organization\Models\Region;
use App\Support\Enums\MemberStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $pusatUnit = OrganizationUnit::where('code', 'AAI-PUSAT')->first();
        $pusatPeriod = OrganizationPeriod::where('organization_unit_id', $pusatUnit->id)->first();
        $anri = Institution::where('name', 'LIKE', '%Arsip Nasional%')->first();
        $bca = Institution::where('name', 'LIKE', '%Bank Central Asia%')->first();
        $dki = Region::where('code', '31')->first();

        // 1. Create Ketua Umum AAI (Dr. H. Andi Kasman / Sample Name)
        $userKetum = User::create([
            'name' => 'Drs. Bamban Saputra, M.Hum.',
            'email' => 'ketum@aai.or.id',
            'username' => 'bamban.saputra',
            'password' => Hash::make('password'),
            'phone' => '081112223334',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $userKetum->assignRole(['ketua-umum', 'anggota']);

        $memberKetum = Member::create([
            'user_id' => $userKetum->id,
            'member_number' => 'AAI-2024-00001',
            'nik' => '3174010101700001',
            'nip' => '197001011995031001',
            'name' => $userKetum->name,
            'gender' => 'L',
            'birth_date' => '1970-01-01',
            'birth_place' => 'Jakarta',
            'institution_id' => $anri?->id,
            'position' => 'Arsiparis Ahli Utama',
            'jenjang_arsiparis' => 'Ahli Utama',
            'golongan' => 'IV/d',
            'education' => 'S2 Ilmu Kearsipan / Sejarah',
            'phone' => $userKetum->phone,
            'address' => 'Jl. Cilandak Indah Blok A No. 10',
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'status' => MemberStatus::Active,
            'region_id' => $dki?->id,
            'organization_unit_id' => $pusatUnit?->id,
            'registered_at' => now()->subYear(),
            'approved_at' => now()->subYear(),
            'approved_by' => 1, // Super Admin
        ]);

        MemberCard::create([
            'member_id' => $memberKetum->id,
            'card_number' => 'CARD-2024-00001',
            'qr_code' => 'QR-AAI-2024-00001',
            'qr_data' => json_encode(['no' => 'AAI-2024-00001', 'name' => $memberKetum->name, 'status' => 'Aktif']),
            'template' => 'gold_premium',
            'issued_at' => now()->subMonths(6),
            'expired_at' => now()->addYears(2),
            'status' => 'active',
        ]);

        if ($pusatPeriod) {
            OrganizationMember::create([
                'organization_period_id' => $pusatPeriod->id,
                'member_id' => $memberKetum->id,
                'position' => 'Ketua Umum',
                'position_category' => 'Pimpinan',
                'sort_order' => 1,
                'status' => 'active',
            ]);
        }

        // 2. Create Sekretaris Jenderal
        $userSekjen = User::create([
            'name' => 'Dra. Rini Wulandari, M.Si.',
            'email' => 'sekjen@aai.or.id',
            'username' => 'rini.wulandari',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $userSekjen->assignRole(['sekretariat-nasional', 'anggota']);

        $memberSekjen = Member::create([
            'user_id' => $userSekjen->id,
            'member_number' => 'AAI-2024-00002',
            'nik' => '3174010202750002',
            'nip' => '197502021998032002',
            'name' => $userSekjen->name,
            'gender' => 'P',
            'birth_date' => '1975-02-02',
            'birth_place' => 'Bandung',
            'institution_id' => $anri?->id,
            'position' => 'Arsiparis Ahli Madya',
            'jenjang_arsiparis' => 'Ahli Madya',
            'golongan' => 'IV/a',
            'education' => 'S2 Ilmu Administrasi',
            'phone' => $userSekjen->phone,
            'address' => 'Jl. Ampera Blok B No. 5',
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'status' => MemberStatus::Active,
            'region_id' => $dki?->id,
            'organization_unit_id' => $pusatUnit?->id,
            'registered_at' => now()->subYear(),
            'approved_at' => now()->subYear(),
            'approved_by' => 1,
        ]);

        MemberCard::create([
            'member_id' => $memberSekjen->id,
            'card_number' => 'CARD-2024-00002',
            'qr_code' => 'QR-AAI-2024-00002',
            'qr_data' => json_encode(['no' => 'AAI-2024-00002', 'name' => $memberSekjen->name, 'status' => 'Aktif']),
            'template' => 'standard',
            'issued_at' => now()->subMonths(6),
            'expired_at' => now()->addYears(2),
            'status' => 'active',
        ]);

        if ($pusatPeriod) {
            OrganizationMember::create([
                'organization_period_id' => $pusatPeriod->id,
                'member_id' => $memberSekjen->id,
                'position' => 'Sekretaris Jenderal',
                'position_category' => 'Pimpinan',
                'sort_order' => 2,
                'status' => 'active',
            ]);
        }

        // 3. Create Bendahara Nasional
        $userBendahara = User::create([
            'name' => 'Sari Rahayu, S.E., S.I.P.',
            'email' => 'bendahara@aai.or.id',
            'username' => 'sari.rahayu',
            'password' => Hash::make('password'),
            'phone' => '081398765432',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $userBendahara->assignRole(['bendahara-nasional', 'anggota']);

        $memberBendahara = Member::create([
            'user_id' => $userBendahara->id,
            'member_number' => 'AAI-2024-00003',
            'nik' => '3174010303800003',
            'nip' => '198003032005012001',
            'name' => $userBendahara->name,
            'gender' => 'P',
            'birth_date' => '1980-03-03',
            'birth_place' => 'Yogyakarta',
            'institution_id' => $anri?->id,
            'position' => 'Arsiparis Ahli Muda',
            'jenjang_arsiparis' => 'Ahli Muda',
            'golongan' => 'III/d',
            'education' => 'S1 Ilmu Kearsipan / Ekonomi',
            'phone' => $userBendahara->phone,
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'status' => MemberStatus::Active,
            'region_id' => $dki?->id,
            'organization_unit_id' => $pusatUnit?->id,
            'registered_at' => now()->subMonths(11),
            'approved_at' => now()->subMonths(11),
            'approved_by' => 1,
        ]);

        if ($pusatPeriod) {
            OrganizationMember::create([
                'organization_period_id' => $pusatPeriod->id,
                'member_id' => $memberBendahara->id,
                'position' => 'Bendahara Nasional',
                'position_category' => 'Pimpinan',
                'sort_order' => 3,
                'status' => 'active',
            ]);
        }

        // 4. Create sample general member (Pending Verification)
        $userPending = User::create([
            'name' => 'Ahmad Fauzi, A.Md.',
            'email' => 'fauzi.arsip@gmail.com',
            'username' => 'ahmad.fauzi',
            'password' => Hash::make('password'),
            'phone' => '085712341234',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $userPending->assignRole(['anggota']);

        Member::create([
            'user_id' => $userPending->id,
            'nik' => '3273010404880004',
            'name' => $userPending->name,
            'gender' => 'L',
            'position' => 'Arsiparis Terampil',
            'jenjang_arsiparis' => 'Terampil',
            'golongan' => 'II/c',
            'education' => 'D3 Kearsipan',
            'phone' => $userPending->phone,
            'city' => 'Kota Bandung',
            'province' => 'Jawa Barat',
            'status' => MemberStatus::Pending,
            'registered_at' => now()->subDays(2),
        ]);

        // 5. Create Swasta Member (Active)
        $userSwasta = User::create([
            'name' => 'Budi Santoso, S.Kom., M.TI.',
            'email' => 'budi.santoso@bca.co.id',
            'username' => 'budi.santoso',
            'password' => Hash::make('password'),
            'phone' => '081223344556',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $userSwasta->assignRole(['anggota']);

        $memberSwasta = Member::create([
            'user_id' => $userSwasta->id,
            'member_number' => 'AAI-2024-00004',
            'nik' => '3174010505900005',
            'nip' => 'BCA-990102',
            'name' => $userSwasta->name,
            'gender' => 'L',
            'birth_date' => '1990-05-05',
            'birth_place' => 'Surabaya',
            'institution_id' => $bca?->id,
            'position' => 'Corporate Records Manager',
            'jenjang_arsiparis' => 'Ahli Pertama',
            'golongan' => 'Swasta/Corporate',
            'education' => 'S2 Teknologi Informasi',
            'phone' => $userSwasta->phone,
            'city' => 'Jakarta Pusat',
            'province' => 'DKI Jakarta',
            'status' => MemberStatus::Active,
            'region_id' => $dki?->id,
            'organization_unit_id' => $pusatUnit?->id,
            'registered_at' => now()->subMonths(3),
            'approved_at' => now()->subMonths(2),
            'approved_by' => 1,
        ]);

        MemberCard::create([
            'member_id' => $memberSwasta->id,
            'card_number' => 'CARD-2024-00004',
            'qr_code' => 'QR-AAI-2024-00004',
            'qr_data' => json_encode(['no' => 'AAI-2024-00004', 'name' => $memberSwasta->name, 'status' => 'Aktif']),
            'template' => 'standard',
            'issued_at' => now()->subMonths(2),
            'expired_at' => now()->addYears(2),
            'status' => 'active',
        ]);
    }
}
