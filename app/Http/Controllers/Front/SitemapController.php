<?php

namespace App\Http\Controllers\Front;

use App\Domain\CMS\Models\Agenda;
use App\Domain\CMS\Models\Article;
use App\Domain\CMS\Models\ArticleCategory;
use App\Domain\CMS\Models\Faq;
use App\Domain\Event\Models\Event;
use App\Http\Controllers\Controller;
use App\Support\Enums\ArticleStatus;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml (cached 1 hour)
     */
    public function sitemap(): Response
    {
        $xml = Cache::remember('front:sitemap-xml', 3600, function () {
            return $this->buildSitemapXml();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Build sitemap XML content.
     */
    private function buildSitemapXml(): string
    {
        $baseUrl = rtrim(config('app.url'), '/');

        // Static pages
        $staticPages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/about/aai-pn', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/organization', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/annual-report', 'priority' => '0.6', 'changefreq' => 'yearly'],
            ['url' => '/careers', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => '/regulations', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => '/news', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/article', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/agenda', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['url' => '/gallery', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => '/faq', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => '/contact', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => '/downloads', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => '/membership/register', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/membership/verify', 'priority' => '0.6', 'changefreq' => 'yearly'],
            ['url' => '/certification/verify', 'priority' => '0.6', 'changefreq' => 'yearly'],
            ['url' => '/privacy-policy', 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => '/terms', 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        // Dynamic articles & news (published)
        $articles = Article::where('status', ArticleStatus::Published)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->get(['slug', 'type', 'published_at', 'updated_at']);

        // Dynamic pages (type=page, published)
        $pages = Article::where('type', 'page')
            ->where('status', ArticleStatus::Published)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        // Events (published)
        $events = Event::published()
            ->orderBy('event_start', 'desc')
            ->get(['slug', 'updated_at']);

        // Article categories
        $categories = ArticleCategory::active()->orderBy('name')->get(['slug', 'updated_at']);

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        // Static pages
        foreach ($staticPages as $page) {
            $xml .= $this->urlEntry(
                $baseUrl . $page['url'],
                now()->toW3cString(),
                $page['changefreq'],
                $page['priority']
            );
        }

        // CMS Pages (type=page)
        foreach ($pages as $page) {
            $xml .= $this->urlEntry(
                $baseUrl . '/page/' . $page->slug,
                ($page->updated_at ?? now())->toW3cString(),
                'monthly',
                '0.6'
            );
        }

        // Articles & News
        foreach ($articles as $article) {
            $type = $article->type?->value ?? 'news';
            $prefix = ($type === 'news' || $type === 'article') ? '/news/' : '/article/';
            $xml .= $this->urlEntry(
                $baseUrl . $prefix . $article->slug,
                ($article->updated_at ?? $article->published_at)->toW3cString(),
                'weekly',
                '0.7'
            );
        }

        // Category archive pages
        foreach ($categories as $cat) {
            $xml .= $this->urlEntry(
                $baseUrl . '/article?category=' . $cat->slug,
                ($cat->updated_at ?? now())->toW3cString(),
                'weekly',
                '0.5'
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate llms.txt for AI search crawlers (ChatGPT, Perplexity, Google AI, etc.)
     */
    public function llmsTxt(): Response
    {
        $siteName = config('app.name', 'AAI Portal Nasional');
        $baseUrl = rtrim(config('app.url'), '/');

        $content = "# {$siteName}\n";
        $content .= "> Portal resmi Pengurus Nasional Asosiasi Arsiparis Indonesia (AAI-PN). Wadah profesi arsiparis seluruh Indonesia.\n\n";

        $content .= "## Tentang\n";
        $content .= "Asosiasi Arsiparis Indonesia (AAI) adalah organisasi profesi arsiparis di Indonesia yang berdiri berdasarkan ketentuan UU No. 43 Tahun 2009 tentang Kearsipan. ";
        $content .= "AAI berperan dalam pengembangan kompetensi arsiparis, sertifikasi profesi melalui LSP AAI, penyelenggaraan kegiatan ilmiah, dan advokasi kebijakan kearsipan nasional.\n\n";

        $content .= "## Fitur Utama\n";
        $content .= "- Manajemen Keanggotaan dan Kartu Tanda Anggota (KTA) Digital\n";
        $content .= "- Lembaga Sertifikasi Profesi (LSP) AAI – Sertifikasi Kompetensi Arsiparis\n";
        $content .= "- E-Voting untuk Pemilihan Pengurus\n";
        $content .= "- Manajemen Event, Diklat, dan Seminar Kearsipan\n";
        $content .= "- Berita, Artikel, dan Publikasi Kearsipan\n";
        $content .= "- Verifikasi digital sertifikat dan KTA\n\n";

        $content .= "## Halaman Penting\n";
        $content .= "- [{$baseUrl}/]({$baseUrl}/): Beranda\n";
        $content .= "- [{$baseUrl}/about]({$baseUrl}/about): Tentang AAI\n";
        $content .= "- [{$baseUrl}/organization]({$baseUrl}/organization): Struktur Organisasi\n";
        $content .= "- [{$baseUrl}/news]({$baseUrl}/news): Berita & Artikel Terbaru\n";
        $content .= "- [{$baseUrl}/agenda]({$baseUrl}/agenda): Agenda Kegiatan\n";
        $content .= "- [{$baseUrl}/membership/register]({$baseUrl}/membership/register): Pendaftaran Anggota\n";
        $content .= "- [{$baseUrl}/membership/verify]({$baseUrl}/membership/verify): Verifikasi KTA\n";
        $content .= "- [{$baseUrl}/certification/verify]({$baseUrl}/certification/verify): Verifikasi Sertifikat LSP\n";
        $content .= "- [{$baseUrl}/faq]({$baseUrl}/faq): FAQ\n";
        $content .= "- [{$baseUrl}/contact]({$baseUrl}/contact): Hubungi Kami\n\n";

        // Add latest published articles
        $latestArticles = Article::where('status', ArticleStatus::Published)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get(['title', 'slug', 'excerpt', 'type', 'published_at']);

        if ($latestArticles->count() > 0) {
            $content .= "## Artikel & Berita Terbaru\n";
            foreach ($latestArticles as $article) {
                $prefix = ($article->type?->value === 'news') ? '/news/' : '/article/';
                $date = $article->published_at?->format('d M Y') ?? '';
                $content .= "- [{$article->title}]({$baseUrl}{$prefix}{$article->slug}) ({$date})";
                if ($article->excerpt) {
                    $content .= ": " . \Illuminate\Support\Str::limit(strip_tags($article->excerpt), 150);
                }
                $content .= "\n";
            }
            $content .= "\n";
        }

        // Add categories
        $categories = ArticleCategory::active()->orderBy('name')->get(['name', 'slug', 'description']);
        if ($categories->count() > 0) {
            $content .= "## Kategori Konten\n";
            foreach ($categories as $cat) {
                $content .= "- [{$cat->name}]({$baseUrl}/article?category={$cat->slug})";
                if ($cat->description) {
                    $content .= ": {$cat->description}";
                }
                $content .= "\n";
            }
            $content .= "\n";
        }

        $content .= "## Kontak\n";
        $content .= "Email: sekretariat@aai.or.id\n";
        $content .= "Website: {$baseUrl}\n";

        return response($content, 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }

    /**
     * Helper: Build single URL entry for sitemap XML.
     */
    private function urlEntry(string $loc, string $lastmod, string $changefreq, string $priority): string
    {
        return "  <url>\n"
            . "    <loc>" . htmlspecialchars($loc) . "</loc>\n"
            . "    <lastmod>{$lastmod}</lastmod>\n"
            . "    <changefreq>{$changefreq}</changefreq>\n"
            . "    <priority>{$priority}</priority>\n"
            . "  </url>\n";
    }
}
