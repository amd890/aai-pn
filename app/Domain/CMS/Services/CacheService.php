<?php

namespace App\Domain\CMS\Services;

use Illuminate\Support\Facades\Cache;

/**
 * CacheService — Invalidation terpusat untuk semua cache frontend.
 *
 * Panggil method-method ini dari Livewire admin components ketika
 * konten di-create, update, atau delete agar halaman publik selalu fresh.
 */
class CacheService
{
    /**
     * Bersihkan SEMUA cache frontend sekaligus.
     * Gunakan ketika deploy baru atau setelah perubahan besar.
     */
    public static function flushAll(): void
    {
        self::flushMenus();
        self::flushHome();
        self::flushArticles();
        self::flushPages();
        self::flushSitemap();
    }

    /**
     * Bersihkan cache menu navigasi (header + footer).
     * Panggil dari MenuManager setelah create/edit/delete/reorder.
     */
    public static function flushMenus(): void
    {
        Cache::forget('front:nav-menus');
    }

    /**
     * Bersihkan cache halaman beranda.
     * Panggil setelah update artikel, agenda, banner, atau data anggota.
     */
    public static function flushHome(): void
    {
        Cache::forget('front:home-stats');
        Cache::forget('front:home-news');
        Cache::forget('front:home-agendas');
        Cache::forget('front:home-banners');
    }

    /**
     * Bersihkan cache daftar artikel & detail artikel.
     * Panggil dari ArticleManager setelah create/edit/delete.
     */
    public static function flushArticles(?string $slug = null): void
    {
        // Flush listing cache (kita tidak tahu persis key mana yang terdampak, jadi flush pattern)
        // Untuk file cache driver, kita forget key-key yang umum
        for ($page = 1; $page <= 20; $page++) {
            // Tanpa filter
            Cache::forget('front:articles:' . md5(json_encode(['category' => null, 'search' => null]) . ':p' . $page));
        }

        // Flush specific article if slug provided
        if ($slug) {
            Cache::forget("front:article:{$slug}");
            Cache::forget("front:article:{$slug}:related");
        }

        // Flush home news (karena bisa berubah)
        Cache::forget('front:home-news');
    }

    /**
     * Bersihkan cache halaman statis CMS.
     * Panggil dari PageManager setelah create/edit/delete.
     */
    public static function flushPages(?string $slug = null): void
    {
        if ($slug) {
            Cache::forget("front:page:{$slug}");
        }
    }

    /**
     * Bersihkan cache sitemap XML.
     * Panggil setelah publish/unpublish konten apa pun.
     */
    public static function flushSitemap(): void
    {
        Cache::forget('front:sitemap-xml');
    }

    /**
     * Bersihkan cache setelah perubahan data keanggotaan.
     * Panggil dari VerificationQueue atau saat approve member.
     */
    public static function flushMembership(): void
    {
        Cache::forget('front:home-stats');
    }
}
