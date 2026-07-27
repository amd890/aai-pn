<?php

namespace Database\Seeders;

use App\Domain\CMS\Models\Agenda;
use App\Domain\CMS\Models\Article;
use App\Domain\CMS\Models\ArticleCategory;
use App\Domain\CMS\Models\Banner;
use App\Domain\CMS\Models\Faq;
use App\Domain\Event\Models\Event;
use App\Domain\Event\Models\EventRegistration;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Support\Enums\ArticleStatus;
use App\Support\Enums\ArticleType;
use App\Support\Enums\EventFormat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentAndEventSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Article Categories
        $catBerita = ArticleCategory::create([
            'name' => 'Berita Nasional',
            'slug' => 'berita-nasional',
            'description' => 'Berita kegiatan dan kebijakan kearsipan nasional',
            'is_active' => true,
        ]);

        $catKegiatan = ArticleCategory::create([
            'name' => 'Kegiatan Wilayah & Cabang',
            'slug' => 'kegiatan-wilayah-cabang',
            'description' => 'Aktivitas pengurus wilayah dan cabang seluruh Indonesia',
            'is_active' => true,
        ]);

        $catArtikel = ArticleCategory::create([
            'name' => 'Opini & Kajian Kearsipan',
            'slug' => 'opini-kajian-kearsipan',
            'description' => 'Artikel ilmiah, best practice, dan kajian transformasi digital arsip',
            'is_active' => true,
        ]);

        // 2. Sample News & Articles
        Article::create([
            'category_id' => $catBerita->id,
            'title' => 'AAI Gelar Rapat Kerja Nasional 2024: Menuju Transformasi Kearsipan Digital',
            'slug' => 'aai-gelar-rapat-kerja-nasional-2024-menuju-transformasi-kearsipan-digital',
            'excerpt' => 'Asosiasi Arsiparis Indonesia (AAI) menyelenggarakan Rakernas 2024 dengan fokus utama penguatan kompentensi arsiparis di era kecerdasan buatan dan cloud computing.',
            'content' => "<p>Jakarta — Asosiasi Arsiparis Indonesia (AAI) resmi membuka Rapat Kerja Nasional (Rakernas) tahun 2024 di Jakarta. Dalam acara yang dihadiri oleh ratusan perwakilan pengurus wilayah dan cabang dari seluruh Indonesia ini, Ketua Umum AAI menekankan pentingnya adopsi teknologi digital dalam pengelolaan arsip nasional.</p><p>\"Arsiparis bukan lagi sekadar penjaga dokumen fisik, melainkan manajer informasi dan data berharga bangsa yang terstruktur dan aman di era digital,\" ujar Drs. Bamban Saputra dalam sambutannya.</p><p>Rakernas kali ini menghasilkan sejumlah rekomendasi strategis, termasuk pengembangan Modul Pembelajaran Digital AAI dan peningkatan standar sertifikasi kompetensi melalui LSP AAI.</p>",
            'type' => ArticleType::News,
            'status' => ArticleStatus::Published,
            'published_at' => now()->subDays(5),
            'author_id' => 1,
            'view_count' => 245,
            'is_featured' => true,
            'is_pinned' => true,
        ]);

        Article::create([
            'category_id' => $catArtikel->id,
            'title' => 'Peran Strategis Arsiparis dalam Efektivitas Transformasi Digital Kearsipan (Kearsipan Digital)',
            'slug' => 'peran-strategis-arsiparis-dalam-efektivitas-kearsipan digital',
            'excerpt' => 'Implementasi Kearsipan Digital membutuhkan komitmen tata kelola arsip dinamis yang tangguh dan selaras dengan standar keamanan informasi internasional.',
            'content' => "<p>Transformasi Digital Kearsipan yang diamanatkan melalui Perpres No. 95 Tahun 2018 telah membawa pergeseran paradigma birokrasi pemerintahan. Salah satu fondasi utama keberhasilan Kearsipan Digital adalah keterpaduan data dan informasi yang dikelola dalam Aplikasi Sistem Persuratan (Sistem Informasi Kearsipan Dinamis Terintegrasi).</p><p>Arsiparis berperan vital dalam memastikan klasifikasi arsip, jadwal retensi arsip, serta sistem klasifikasi keamanan dan akses berberkas berjalan akurat pada infrastruktur digital.</p>",
            'type' => ArticleType::Article,
            'status' => ArticleStatus::Published,
            'published_at' => now()->subDays(2),
            'author_id' => 1,
            'view_count' => 120,
            'is_featured' => false,
        ]);

        // 3. Agendas
        Agenda::create([
            'title' => 'Workshop Manajemen Arsip Dinamis & Digitalisasi di Aplikasi Sistem Persuratan',
            'slug' => 'workshop-manajemen-arsip-dinamis-persuratan-2024',
            'description' => 'Pelatihan intensif dua hari mengenai penerapan aplikasi Sistem Persuratan versi terbaru bagi arsiparis instansi pemerintah dan universitas.',
            'content' => 'Pelatihan diselenggarakan secara hybrid (luring di Hotel Menara Peninsula Jakarta dan daring melalui Zoom IAI). Peserta mendapatkan sertifikat 16 JPL.',
            'location' => 'Hotel Menara Peninsula, Jakarta & Zoom Hybrid',
            'start_date' => now()->addDays(14)->setTime(8, 30),
            'end_date' => now()->addDays(15)->setTime(16, 30),
            'status' => 'upcoming',
            'is_featured' => true,
            'created_by' => 1,
        ]);

        // 4. Banners
        Banner::create([
            'title' => 'Selamat Datang di Portal Asosiasi Arsiparis Indonesia',
            'image_path' => 'banners/hero-aai-portal.jpg',
            'url' => '/membership/register',
            'description' => 'Wadah profesi arsiparis Indonesia menuju ekosistem kearsipan digital yang handal, berdaya saing, dan berintegritas tinggi.',
            'sort_order' => 1,
            'is_active' => true,
            'start_date' => now()->subMonths(1),
            'end_date' => now()->addYears(1),
        ]);

        // 5. FAQ
        $faqs = [
            [
                'question' => 'Bagaimana cara mendaftar menjadi anggota AAI?',
                'answer' => 'Anda dapat mendaftar melalui menu Membership -> Register dengan mengisi nomor KTP/NIP, mengunggah SK Jabatan Terakhir atau Ijazah Bidang Kearsipan, serta membayar iuran wajib bulanan/tahunan.',
                'category' => 'Keanggotaan',
                'sort_order' => 1,
            ],
            [
                'question' => 'Berapa biaya iuran keanggotaan tahunan AAI?',
                'answer' => 'Iuran keanggotaan wajib tahunan adalah Rp 120.000 (seratus dua puluh ribu rupiah) atau Rp 10.000 per bulan, yang dapat dibayarkan langsung via Virtual Account, Bank Transfer, atau QRIS di portal ini.',
                'category' => 'Keuangan & Iuran',
                'sort_order' => 2,
            ],
            [
                'question' => 'Apakah sertifikat keanggotaan dan Kartu Tanda Anggota (KTA) berlaku nasional?',
                'answer' => 'Ya. KTA AAI berbentuk digital (E-Card) dengan pengamanan kode QR terenkripsi yang langsung terkoneksi dengan database nasional AAI dan dapat diverifikasi kapan pun secara real-time.',
                'category' => 'Keanggotaan',
                'sort_order' => 3,
            ],
            [
                'question' => 'Bagaimana cara mengikuti Uji Kompetensi dan Sertifikasi melalui LSP AAI?',
                'answer' => 'Informasi skema sertifikasi, syarat dokumen, serta pendaftaran jadwal Uji Kompetensi (TUK) tersedia secara khusus bagi anggota aktif di menu LSP atau langsung di Member Area.',
                'category' => 'Sertifikasi & LSP',
                'sort_order' => 4,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_active' => true]));
        }

        // 6. Events & Registrations
        $pusat = OrganizationUnit::where('code', 'AAI-PUSAT')->first();
        $event = Event::create([
            'title' => 'Simposium Nasional Ilmu Kearsipan & Transformasi Digital AAI 2024',
            'slug' => 'simposium-nasional-kearsipan-2024',
            'description' => 'Konferensi tahunan terbesar arsiparis Indonesia dengan pembicara ahli kearsipan dari ANRI, akademisi, dan pakar teknologi informasi internasional.',
            'content' => "<p>Simposium Nasional AAI 2024 menghadirkan sesi diskusi panel mengenai manajemen arsip statis, restorasi digital, preservasi cloud, serta implementasi kecerdasan buatan dalam ekstraksi informasi kearsipan.</p>",
            'type' => 'Simposium & Seminar Nasional',
            'format' => EventFormat::Hybrid,
            'location' => 'Auditorium Utama Arsip Nasional RI, Jakarta Selatan & Zoom Streaming',
            'map_url' => 'https://maps.google.com/?q=Arsip+Nasional+Republik+Indonesia',
            'quota' => 500,
            'price' => 250000, // Rp 250.000 untuk anggota
            'is_free' => false,
            'registration_start' => now()->subDays(15),
            'registration_end' => now()->addDays(10),
            'event_start' => now()->addDays(12)->setTime(8, 0),
            'event_end' => now()->addDays(13)->setTime(16, 0),
            'status' => 'published',
            'organization_unit_id' => $pusat?->id,
            'created_by' => 1,
        ]);

        // Enroll sample members into event
        $members = Member::active()->take(3)->get();
        foreach ($members as $mem) {
            EventRegistration::create([
                'event_id' => $event->id,
                'member_id' => $mem->id,
                'status' => 'confirmed',
                'payment_id' => null,
                'registered_at' => now()->subDays(5),
                'confirmed_at' => now()->subDays(4),
                'notes' => 'Pendaftaran online terkonfirmasi',
            ]);
        }
    }
}
