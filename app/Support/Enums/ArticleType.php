<?php

namespace App\Support\Enums;

enum ArticleType: string
{
    case Article = 'article';
    case News = 'news';
    case Page = 'page';

    public function label(): string
    {
        return match ($this) {
            self::Article => 'Artikel',
            self::News => 'Berita',
            self::Page => 'Halaman Statis',
        };
    }
}
