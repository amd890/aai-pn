<?php

namespace App\Console\Commands;

use App\Domain\CMS\Services\CacheService;
use Illuminate\Console\Command;

class FlushFrontCache extends Command
{
    protected $signature = 'cache:flush-front';
    protected $description = 'Flush all frontend caches (menus, homepage, articles, pages, sitemap)';

    public function handle(): int
    {
        CacheService::flushAll();
        $this->info('✅ Semua cache frontend berhasil dihapus!');

        return self::SUCCESS;
    }
}
