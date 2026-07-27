<?php

namespace App\Support\Enums;

enum ArticleStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf',
            self::Published => 'Dipublikasikan',
            self::Archived => 'Diarsipkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'green',
            self::Archived => 'yellow',
        };
    }
}
