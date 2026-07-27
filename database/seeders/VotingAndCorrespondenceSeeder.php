<?php

namespace Database\Seeders;

use App\Domain\Correspondence\Models\LetterIn;
use App\Domain\Correspondence\Models\LetterNumber;
use App\Domain\Correspondence\Models\LetterOut;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Domain\Voting\Models\Election;
use App\Domain\Voting\Models\ElectionCandidate;
use App\Support\Enums\ElectionLevel;
use App\Support\Enums\ElectionStatus;
use Illuminate\Database\Seeder;

class VotingAndCorrespondenceSeeder extends Seeder
{
    public function run(): void
    {
        $pusat = OrganizationUnit::where('code', 'AAI-PUSAT')->first();
        $members = Member::active()->take(3)->get();

        // ── 1. Voting / E-Election (Musyawarah Nasional) ─────────
        if ($pusat && $members->count() >= 2) {
            $election = Election::create([
                'title' => 'Pemilihan Calon Ketua Umum AAI Periode 2028-2032 (Simulasi Munas)',
                'description' => 'Pemilihan elektronik (E-Voting) Ketua Umum Asosiasi Arsiparis Indonesia dalam forum Musyawarah Nasional dengan sistem satu anggota satu suara.',
                'level' => ElectionLevel::Nasional,
                'type' => 'Pemilihan Ketua Umum',
                'organization_unit_id' => $pusat->id,
                'start_at' => now()->addDays(20)->setTime(9, 0),
                'end_at' => now()->addDays(20)->setTime(16, 0),
                'status' => ElectionStatus::Open,
                'max_vote' => 1,
                'require_otp' => true,
                'eligible_criteria' => json_encode(['member_status' => 'active', 'min_membership_months' => 3]),
                'created_by' => 1,
            ]);

            ElectionCandidate::create([
                'election_id' => $election->id,
                'member_id' => $members[0]->id,
                'candidate_number' => 1,
                'vision_mission' => "Visi: Mendorong Transformasi Arsiparis Global Terdepan.\nMisi: 1. Digitalisasi sistem sertifikasi dan pembekalan gratis.\n2. Advokasi kesejahteraan dan tunjangan profesi arsiparis Indonesia.",
                'profile_summary' => 'Arsiparis Ahli Utama dengan pengalaman pengabdian di kearsipan nasional lebih dari 25 tahun.',
                'status' => 'active',
                'vote_count' => 0,
            ]);

            ElectionCandidate::create([
                'election_id' => $election->id,
                'member_id' => $members[1]->id,
                'candidate_number' => 2,
                'vision_mission' => "Visi: Kolaboratif, Modern, dan Inklusif bagi Seluruh Arsiparis Muda & Senior.\nMisi: 1. Kolaborasi internasional dengan ICA (International Council on Archives).\n2. Pembentukan Jaringan Arsiparis Universitas dan Pemda.",
                'profile_summary' => 'Arsiparis Ahli Madya, akademisi dan peneliti tata kelola informasi pemerintahan berbasis elektronik (Kearsipan Digital).',
                'status' => 'active',
                'vote_count' => 0,
            ]);
        }

        // ── 2. Correspondence / Tata Naskah Dinas ─────────────────
        if ($pusat) {
            LetterNumber::create([
                'organization_unit_id' => $pusat->id,
                'format_template' => '{no}/SK/AAI-PUSAT/XI/{tahun}',
                'last_number' => 15,
                'year' => 2024,
                'prefix' => 'SK',
                'suffix' => 'XI',
            ]);

            LetterNumber::create([
                'organization_unit_id' => $pusat->id,
                'format_template' => '{no}/UND/AAI-PUSAT/XI/{tahun}',
                'last_number' => 84,
                'year' => 2024,
                'prefix' => 'UND',
                'suffix' => 'XI',
            ]);

            LetterIn::create([
                'letter_number' => 'B-2401/ANRI/KP.01/10/2024',
                'sender' => 'Deputi Bidang Pembinaan Kearsipan - ANRI',
                'sender_institution' => 'Arsip Nasional Republik Indonesia',
                'subject' => 'Undangan Rapat Koordinasi Pembinaan Profesi Arsiparis Nasional',
                'description' => 'Permohonan partisipasi Ketua Umum dan Dewan Pengurus AAI dalam forum pembahasan draf Perda Kearsipan.',
                'received_date' => now()->subDays(3)->toDateString(),
                'letter_date' => now()->subDays(5)->toDateString(),
                'classification' => 'Undangan / Resmi',
                'disposition' => 'Diteruskan kepada Sekretaris Jenderal dan Ketua Bidang Pengkajian Kompetensi',
                'organization_unit_id' => $pusat->id,
                'received_by' => 2, // Administrator or Sekjen
                'notes' => 'Segera diproses sebelum Jumat',
            ]);

            LetterOut::create([
                'letter_number' => '085/UND/AAI-PUSAT/XI/2024',
                'recipient' => 'Seluruh Ketua Pengurus Wilayah & Cabang AAI se-Indonesia',
                'recipient_institution' => 'Pengurus Wilayah & Cabang AAI',
                'subject' => 'Undangan Menghadiri Rapat Koordinasi Nasional (Rakornas) IV AAI 2024',
                'content' => "<p>Dengan hormat,</p><p>Sehubungan dengan penyelesaian agenda evaluasi tahunan keanggotaan dan pemantapan program Uji Kompetensi LSP AAI tahun 2025, Dewan Pengurus Nasional Asosiasi Arsiparis Indonesia mengundang Saudara/i untuk hadir pada rapat secara hybrid...</p>",
                'letter_date' => now()->subDay()->toDateString(),
                'signed_by' => 1,
                'signer_position' => 'Ketua Umum AAI',
                'qr_code' => 'QR-LTR-OUT-2024-0085',
                'classification' => 'Penting / Internal',
                'organization_unit_id' => $pusat->id,
                'status' => 'signed',
                'created_by' => 1,
                'notes' => 'Telah ditandatangani secara digital oleh Ketua Umum dan didistribusikan ke email pengurus wilayah.',
            ]);
        }
    }
}
