<?php

namespace Database\Seeders;

use App\Domain\Core\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Profil Organisasi
            ['group' => 'general', 'key' => 'org_name', 'value' => 'Asosiasi Arsiparis Indonesia (AAI)', 'type' => 'string', 'description' => 'Nama resmi organisasi', 'is_public' => true],
            ['group' => 'general', 'key' => 'org_short_name', 'value' => 'AAI', 'type' => 'string', 'description' => 'Singkatan resmi', 'is_public' => true],
            ['group' => 'general', 'key' => 'org_tagline', 'value' => 'Wadah Profesional Arsiparis Indonesia Berintegritas & Modern', 'type' => 'string', 'description' => 'Tagline utama organisasi', 'is_public' => true],
            ['group' => 'general', 'key' => 'org_email', 'value' => 'sekretariat@aai.or.id', 'type' => 'string', 'description' => 'Email kontak resmi', 'is_public' => true],
            ['group' => 'general', 'key' => 'org_phone', 'value' => '(021) 7805851', 'type' => 'string', 'description' => 'Nomor telepon kantor pusat', 'is_public' => true],
            ['group' => 'general', 'key' => 'org_address', 'value' => 'Gedung Arsip Nasional RI, Jl. Ampera Raya No. 7, Cilandak Timur, Jakarta Selatan 12560', 'type' => 'string', 'description' => 'Alamat kantor pusat AAI', 'is_public' => true],

            // Keanggotaan & Iuran (Fee Configs)
            ['group' => 'membership', 'key' => 'fee_annual_default', 'value' => '120000', 'type' => 'number', 'description' => 'Besaran iuran tahunan anggota (Rp)', 'is_public' => true],
            ['group' => 'membership', 'key' => 'fee_monthly_default', 'value' => '10000', 'type' => 'number', 'description' => 'Besaran iuran bulanan anggota (Rp)', 'is_public' => true],
            ['group' => 'membership', 'key' => 'auto_approve_registration', 'value' => 'false', 'type' => 'boolean', 'description' => 'Apakah pendaftaran anggota baru otomatis disetujui tanpa verifikasi verifikator', 'is_public' => false],
            ['group' => 'membership', 'key' => 'card_default_validity_years', 'value' => '2', 'type' => 'number', 'description' => 'Masa berlaku default KTA digital AAI (tahun)', 'is_public' => false],

            // SEO & Portal Metadata
            ['group' => 'seo', 'key' => 'meta_title_default', 'value' => 'AAI — Asosiasi Arsiparis Indonesia | Portal Keanggotaan & Kearsipan', 'type' => 'string', 'description' => 'Title tag default web portal', 'is_public' => true],
            ['group' => 'seo', 'key' => 'meta_description_default', 'value' => 'Portal Resmi Asosiasi Arsiparis Indonesia (AAI). Manajemen KTA digital, sertifikasi LSP Arsiparis, jadwal kegiatan, dan publikasi kearsipan nasional.', 'type' => 'string', 'description' => 'Meta description default SEO', 'is_public' => true],
            ['group' => 'seo', 'key' => 'meta_keywords_default', 'value' => 'Arsiparis, AAI, Asosiasi Arsiparis Indonesia, Kearsipan, ANRI, Sistem Persuratan, Kearsipan Digital, Sertifikasi Arsiparis, LSP Kearsipan', 'type' => 'string', 'description' => 'Keywords SEO portal AAI', 'is_public' => true],

            // System Security & Features
            ['group' => 'system', 'key' => 'enable_2fa_default', 'value' => 'false', 'type' => 'boolean', 'description' => 'Wajibkan otentikasi dua faktor untuk pengurus admin', 'is_public' => false],
            ['group' => 'system', 'key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'description' => 'Aktifkan mode perbaikan / maintenance portal', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
