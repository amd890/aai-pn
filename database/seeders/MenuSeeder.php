<?php

namespace Database\Seeders;

use App\Domain\CMS\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            // Header Menus
            ['label' => 'Tentang Kami', 'url' => '/about', 'location' => 'header', 'order' => 1],
            ['label' => 'Organisasi Kami', 'url' => '/organization', 'location' => 'header', 'order' => 2],
            ['label' => 'Laporan Tahunan', 'url' => '/annual-report', 'location' => 'header', 'order' => 3],
            ['label' => 'Bekerja di Sektor Arsip', 'url' => '/careers', 'location' => 'header', 'order' => 4],
            ['label' => 'Regulasi', 'url' => '/regulations', 'location' => 'header', 'order' => 5],
            ['label' => 'Blogs', 'url' => '/news', 'location' => 'header', 'order' => 6],

            // Footer: Media & Publikasi
            ['label' => 'Agenda', 'url' => '/agenda', 'location' => 'footer_media', 'order' => 1],
            ['label' => 'Memory Hari Ini', 'url' => '/memory-today', 'location' => 'footer_media', 'order' => 2],
            ['label' => 'Publikasi', 'url' => '/publications', 'location' => 'footer_media', 'order' => 3],
            ['label' => 'Galeri Dokumentasi', 'url' => '/gallery', 'location' => 'footer_media', 'order' => 4],

            // Footer: Layanan Publik
            ['label' => 'Otentikasi KTA Digital', 'url' => '/membership/verify', 'location' => 'footer_services', 'order' => 1],
            ['label' => 'Cek Sertifikat LSP', 'url' => '/certification/verify', 'location' => 'footer_services', 'order' => 2],
            ['label' => 'Bantuan / FAQ', 'url' => '/faq', 'location' => 'footer_services', 'order' => 3],
            ['label' => 'Unduhan Berkas', 'url' => '/downloads', 'location' => 'footer_services', 'order' => 4],
            ['label' => 'Hubungi Kami', 'url' => '/contact', 'location' => 'footer_services', 'order' => 5],

            // Footer: Tautan Eksternal
            ['label' => 'Arsip Nasional (ANRI)', 'url' => 'https://anri.go.id', 'location' => 'footer_external', 'order' => 1, 'target' => '_blank'],
            ['label' => 'KVAN', 'url' => 'https://kvan.nl', 'location' => 'footer_external', 'order' => 2, 'target' => '_blank'],
            ['label' => 'Int. Council of Archive', 'url' => 'https://www.ica.org', 'location' => 'footer_external', 'order' => 3, 'target' => '_blank'],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
