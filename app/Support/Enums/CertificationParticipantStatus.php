<?php

namespace App\Support\Enums;

enum CertificationParticipantStatus: string
{
    case Registered = 'registered';
    case Assessed = 'assessed';
    case Competent = 'competent';
    case NotCompetent = 'not_competent';

    public function label(): string
    {
        return match ($this) {
            self::Registered => 'Terdaftar',
            self::Assessed => 'Sedang Diases',
            self::Competent => 'Kompeten',
            self::NotCompetent => 'Belum Kompeten',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Registered => 'blue',
            self::Assessed => 'yellow',
            self::Competent => 'green',
            self::NotCompetent => 'red',
        };
    }
}
