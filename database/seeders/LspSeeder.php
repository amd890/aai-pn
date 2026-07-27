<?php

namespace Database\Seeders;

use App\Domain\LSP\Models\Assessor;
use App\Domain\LSP\Models\CertificationBatch;
use App\Domain\LSP\Models\CertificationParticipant;
use App\Domain\LSP\Models\CertificationScheme;
use App\Domain\LSP\Models\LspCertificate;
use App\Domain\LSP\Models\Tuk;
use App\Domain\Membership\Models\Member;
use App\Support\Enums\CertificationParticipantStatus;
use Illuminate\Database\Seeder;

class LspSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Certification Schemes
        $skemaAhli = CertificationScheme::create([
            'name' => 'Skema Sertifikasi Kompetensi Arsiparis Tingkat Ahli Muda',
            'code' => 'SKM-AAI-AHLI-MUDA',
            'level' => 'Ahli Muda (KKNI Level 6)',
            'description' => 'Sertifikasi kompetensi standar nasional untuk arsiparis berjabatan fungsional Ahli Muda atau selevel sarjana/berpengalaman di institusi negeri maupun swasta.',
            'requirements' => json_encode([
                'Surat Keputusan Pengangkatan Jabatan Arsiparis / Surat Keterangan Kerja minimal 3 tahun',
                'Ijazah minimal S1/D4 Bidang Kearsipan atau disiplin ilmu lain yang telah mengikuti diklat kearsipan',
                'Kartu Anggota AAI dengan status Aktif (Iuran tahun berjalan lunas)',
            ]),
            'competency_units' => json_encode([
                ['code' => 'N.78ARS00.001.2', 'title' => 'Menyusun Ketentuan/Peraturan Kearsipan Instansi'],
                ['code' => 'N.78ARS00.012.2', 'title' => 'Melakukan Penilaian dan Akuisisi Arsip Statis'],
                ['code' => 'N.78ARS00.018.2', 'title' => 'Melakukan Audit Internal Tata Kelola Kearsipan'],
            ]),
            'is_active' => true,
        ]);

        $skemaTerampil = CertificationScheme::create([
            'name' => 'Skema Sertifikasi Kompetensi Arsiparis Tingkat Terampil',
            'code' => 'SKM-AAI-TERAMPIL',
            'level' => 'Terampil (KKNI Level 5)',
            'description' => 'Sertifikasi kompetensi teknis pengelolaan arsip dinamis dan statis bagi pelaksana atau lulusan Diploma III Kearsipan.',
            'requirements' => json_encode([
                'Ijazah minimal D3 Kearsipan atau setara',
                'Kartu Anggota AAI Aktif',
            ]),
            'competency_units' => json_encode([
                ['code' => 'N.78ARS00.005.1', 'title' => 'Melakukan Pemberkasan Arsip Aktif (Filing System)'],
                ['code' => 'N.78ARS00.008.1', 'title' => 'Melakukan Penyusunan Daftar Arsip Inaktif'],
            ]),
            'is_active' => true,
        ]);

        // 2. Tempat Uji Kompetensi (TUK)
        $tukAnri = Tuk::create([
            'name' => 'TUK Mandiri Arsip Nasional RI (ANRI)',
            'code' => 'TUK-ANRI-01',
            'type' => 'Mandiri',
            'address' => 'Jl. Ampera Raya No. 7, Jakarta Selatan',
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'capacity' => 60,
            'contact_person' => 'Budi Santoso, M.Si.',
            'phone' => '(021) 7805851 ext. 210',
            'is_active' => true,
        ]);

        Tuk::create([
            'name' => 'TUK Sewaktu Universitas Gadjah Mada',
            'code' => 'TUK-UGM-02',
            'type' => 'Sewaktu',
            'address' => 'Sekolah Vokasi UGM, Bulaksumur, Yogyakarta',
            'city' => 'Yogyakarta',
            'province' => 'D.I. Yogyakarta',
            'capacity' => 40,
            'contact_person' => 'Dra. Endang Lestari',
            'phone' => '08122334455',
            'is_active' => true,
        ]);

        // 3. Assessors
        $seniorMembers = Member::active()->take(2)->get();
        if ($seniorMembers->count() > 0) {
            $assessor = Assessor::create([
                'member_id' => $seniorMembers[0]->id,
                'license_number' => 'REG-BNSP-ARS-2023-009',
                'license_expired_at' => now()->addYears(3),
                'specialization' => 'Manajemen Arsip Dinamis & Audit Kearsipan',
                'status' => 'active',
            ]);

            // 4. Batches
            $batch = CertificationBatch::create([
                'scheme_id' => $skemaAhli->id,
                'tuk_id' => $tukAnri->id,
                'batch_number' => 'BATCH-I-2024-Jakarta',
                'scheduled_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(1)->addDays(2),
                'quota' => 30,
                'assessor_id' => $assessor->id,
                'status' => 'open',
                'notes' => 'Pendaftaran gelombang 1 tahun 2024 terbuka untuk umum dan anggota',
            ]);

            // 5. Sample participant
            if (isset($seniorMembers[1])) {
                $participant = CertificationParticipant::create([
                    'batch_id' => $batch->id,
                    'member_id' => $seniorMembers[1]->id,
                    'status' => CertificationParticipantStatus::Competent,
                    'assessment_date' => now()->subMonths(1),
                    'result' => 'Lulus Uji Kompetensi - Kompeten Pada Seluruh Unit',
                    'notes' => 'Direkomendasikan mendapat Sertifikat Kompetensi BNSP-LSP AAI',
                ]);

                LspCertificate::create([
                    'participant_id' => $participant->id,
                    'scheme_id' => $skemaAhli->id,
                    'certificate_number' => 'CERT/LSP-AAI/VIII/2024/0009',
                    'qr_code' => 'QR-LSP-AAI-2024-0009',
                    'issued_at' => now()->subMonths(1),
                    'expired_at' => now()->addYears(3),
                    'status' => 'active',
                ]);
            }
        }
    }
}
