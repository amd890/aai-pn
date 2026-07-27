<?php

namespace Database\Seeders;

use App\Domain\Organization\Models\Institution;
use App\Domain\Organization\Models\OrganizationPeriod;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Domain\Organization\Models\Region;
use App\Support\Enums\OrganizationUnitType;
use App\Support\Enums\RegionType;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Regions (Provinsi dan Kabupaten/Kota contoh)
        $regionsData = [
            ['name' => 'DKI Jakarta', 'code' => '31', 'children' => [
                ['name' => 'Jakarta Pusat', 'code' => '3171'],
                ['name' => 'Jakarta Selatan', 'code' => '3174'],
            ]],
            ['name' => 'Jawa Barat', 'code' => '32', 'children' => [
                ['name' => 'Kota Bandung', 'code' => '3273'],
                ['name' => 'Kota Bogor', 'code' => '3271'],
                ['name' => 'Kota Depok', 'code' => '3276'],
            ]],
            ['name' => 'Jawa Tengah', 'code' => '33', 'children' => [
                ['name' => 'Kota Semarang', 'code' => '3374'],
                ['name' => 'Kota Surakarta', 'code' => '3372'],
            ]],
            ['name' => 'D.I. Yogyakarta', 'code' => '34', 'children' => [
                ['name' => 'Kota Yogyakarta', 'code' => '3471'],
            ]],
            ['name' => 'Jawa Timur', 'code' => '35', 'children' => [
                ['name' => 'Kota Surabaya', 'code' => '3578'],
                ['name' => 'Kota Malang', 'code' => '3573'],
            ]],
            ['name' => 'Banten', 'code' => '36', 'children' => [
                ['name' => 'Kota Tangerang', 'code' => '3671'],
                ['name' => 'Kota Tangerang Selatan', 'code' => '3674'],
            ]],
            ['name' => 'Bali', 'code' => '51', 'children' => [
                ['name' => 'Kota Denpasar', 'code' => '5171'],
            ]],
        ];

        $createdRegions = [];
        foreach ($regionsData as $province) {
            $parent = Region::create([
                'name' => $province['name'],
                'code' => $province['code'],
                'type' => RegionType::Provinsi,
                'is_active' => true,
            ]);
            $createdRegions[$province['code']] = $parent;

            foreach ($province['children'] as $city) {
                Region::create([
                    'name' => $city['name'],
                    'code' => $city['code'],
                    'type' => RegionType::Kabupaten,
                    'parent_id' => $parent->id,
                    'is_active' => true,
                ]);
            }
        }

        // 2. Seed Institutions (Tempat Kerja Arsiparis)
        $institutionsData = [
            [
                'name' => 'Arsip Nasional Republik Indonesia (ANRI)',
                'type' => 'Lembaga Pemerintah Non Kementerian',
                'address' => 'Jl. Ampera Raya No. 7, Cilandak Timur, Jakarta Selatan',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'phone' => '(021) 7805851',
                'email' => 'info@anri.go.id',
                'website' => 'https://www.anri.go.id',
            ],
            [
                'name' => 'Kementerian Dalam Negeri Republik Indonesia',
                'type' => 'Kementerian',
                'address' => 'Jl. Medan Merdeka Utara No. 7, Jakarta Pusat',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
            ],
            [
                'name' => 'Universitas Indonesia',
                'type' => 'Perguruan Tinggi Negeri',
                'address' => 'Kampus UI Depok, Jawa Barat',
                'city' => 'Kota Depok',
                'province' => 'Jawa Barat',
            ],
            [
                'name' => 'Universitas Gadjah Mada',
                'type' => 'Perguruan Tinggi Negeri',
                'address' => 'Bulaksumur, Yogyakarta',
                'city' => 'Kota Yogyakarta',
                'province' => 'D.I. Yogyakarta',
            ],
            [
                'name' => 'Dinas Perpustakaan dan Kearsipan Provinsi Jawa Barat',
                'type' => 'Pemerintah Daerah',
                'address' => 'Jl. Kawaluyaan Indah Raya No. 4, Jatisari, Buahbatu, Bandung',
                'city' => 'Kota Bandung',
                'province' => 'Jawa Barat',
            ],
            [
                'name' => 'PT Bank Central Asia Tbk',
                'type' => 'Perusahaan Swasta',
                'address' => 'Menara BCA, Grand Indonesia, Jl. M.H. Thamrin No. 1, Jakarta Pusat',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
            ],
            [
                'name' => 'PT Astra International Tbk',
                'type' => 'Perusahaan Swasta',
                'address' => 'Menara Astra, Jl. Jend. Sudirman Kav 5-6, Jakarta Pusat',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
            ],
        ];

        foreach ($institutionsData as $inst) {
            Institution::create(array_merge($inst, ['is_active' => true]));
        }

        // 3. Seed Organization Units (Pusat, Wilayah, Cabang)
        $pusat = OrganizationUnit::create([
            'name' => 'Pengurus Nasional AAI (Pusat)',
            'type' => OrganizationUnitType::Pusat,
            'code' => 'AAI-PUSAT',
            'region_id' => $createdRegions['31']->id, // DKI Jakarta as HQ
            'address' => 'Gedung ANRI, Jl. Ampera Raya No. 7, Jakarta Selatan',
            'phone' => '(021) 7805851',
            'email' => 'sekretariat@aai.or.id',
            'status' => 'active',
        ]);

        // Pengurus Wilayah
        $wilayahJabar = OrganizationUnit::create([
            'name' => 'Pengurus Wilayah AAI Jawa Barat',
            'type' => OrganizationUnitType::Wilayah,
            'code' => 'AAI-WIL-JABAR',
            'region_id' => $createdRegions['32']->id,
            'parent_id' => $pusat->id,
            'address' => 'Jl. Kawaluyaan Indah Raya No. 4, Bandung',
            'email' => 'jabar@aai.or.id',
            'status' => 'active',
        ]);

        $wilayahDIY = OrganizationUnit::create([
            'name' => 'Pengurus Wilayah AAI D.I. Yogyakarta',
            'type' => OrganizationUnitType::Wilayah,
            'code' => 'AAI-WIL-DIY',
            'region_id' => $createdRegions['34']->id,
            'parent_id' => $pusat->id,
            'address' => 'Jl. Tentara Pelajar, Yogyakarta',
            'email' => 'diy@aai.or.id',
            'status' => 'active',
        ]);

        $wilayahJatim = OrganizationUnit::create([
            'name' => 'Pengurus Wilayah AAI Jawa Timur',
            'type' => OrganizationUnitType::Wilayah,
            'code' => 'AAI-WIL-JATIM',
            'region_id' => $createdRegions['35']->id,
            'parent_id' => $pusat->id,
            'email' => 'jatim@aai.or.id',
            'status' => 'active',
        ]);

        // Pengurus Cabang
        OrganizationUnit::create([
            'name' => 'Pengurus Cabang AAI Universitas Indonesia',
            'type' => OrganizationUnitType::Cabang,
            'code' => 'AAI-CAB-UI',
            'parent_id' => $wilayahJabar->id,
            'email' => 'cabang.ui@aai.or.id',
            'status' => 'active',
        ]);

        // 4. Seed Organization Period
        OrganizationPeriod::create([
            'organization_unit_id' => $pusat->id,
            'period_name' => 'Kepengurusan Pusat Periode 2024-2028',
            'start_year' => 2024,
            'end_year' => 2028,
            'sk_number' => '001/SK/MUNAS-AAI/IX/2024',
            'status' => 'active',
        ]);

        OrganizationPeriod::create([
            'organization_unit_id' => $wilayahJabar->id,
            'period_name' => 'Kepengurusan Wilayah Jabar Periode 2024-2028',
            'start_year' => 2024,
            'end_year' => 2028,
            'sk_number' => '005/SK/MUSWIL-AAI-JABAR/X/2024',
            'status' => 'active',
        ]);
    }
}
