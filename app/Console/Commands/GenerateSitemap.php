<?php

namespace App\Console\Commands;

use App\Domain\CMS\Models\Article;
use App\Domain\CMS\Models\ArticleCategory;
use App\Domain\Event\Models\Event;
use App\Support\Enums\ArticleStatus;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--output=public/sitemap.xml : Output path for the sitemap file}';
    protected $description = 'Generate static sitemap.xml from published content (articles, pages, events, categories)';

    public function handle(): int
    {
        $this->info('🔄 Generating sitemap...');

        $baseUrl = rtrim(config('app.url'), '/');
        $outputPath = $this->option('output');

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

        // Dynamic content
        $articles = Article::where('status', ArticleStatus::Published)
            ->where('type', '!=', 'page')
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->get(['slug', 'type', 'published_at', 'updated_at']);

        $pages = Article::where('type', 'page')
            ->where('status', ArticleStatus::Published)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        $events = Event::published()
            ->orderBy('event_start', 'desc')
            ->get(['slug', 'updated_at']);

        $categories = ArticleCategory::active()->orderBy('name')->get(['slug', 'updated_at']);

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        $count = 0;

        // Static pages
        foreach ($staticPages as $page) {
            $xml .= $this->urlEntry($baseUrl . $page['url'], now()->toW3cString(), $page['changefreq'], $page['priority']);
            $count++;
        }

        // CMS Pages
        foreach ($pages as $page) {
            $xml .= $this->urlEntry(
                $baseUrl . '/page/' . $page->slug,
                ($page->updated_at ?? now())->toW3cString(),
                'monthly',
                '0.6'
            );
            $count++;
        }

        // Articles & News
        foreach ($articles as $article) {
            $xml .= $this->urlEntry(
                $baseUrl . '/news/' . $article->slug,
                ($article->updated_at ?? $article->published_at)->toW3cString(),
                'weekly',
                '0.7'
            );
            $count++;
        }

        // Categories
        foreach ($categories as $cat) {
            $xml .= $this->urlEntry(
                $baseUrl . '/article?category=' . $cat->slug,
                ($cat->updated_at ?? now())->toW3cString(),
                'weekly',
                '0.5'
            );
            $count++;
        }

        $xml .= '</urlset>';

        // Write to file
        $fullPath = base_path($outputPath);
        file_put_contents($fullPath, $xml);

        $this->info("✅ Sitemap berhasil digenerate: {$fullPath}");
        $this->info("   Total URL: {$count}");
        $this->info("   - Static pages: " . count($staticPages));
        $this->info("   - CMS Pages: " . $pages->count());
        $this->info("   - Articles/News: " . $articles->count());
        $this->info("   - Categories: " . $categories->count());

        return self::SUCCESS;
    }

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
