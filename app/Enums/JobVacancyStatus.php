<?php

namespace App\Enums;

enum JobVacancyStatus: int
{
    case DRAFT = 1;
    case PUBLISHED = 2;
    case INACTIVE = 99;

    public function getLabel(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::INACTIVE => 'Inactive',
        };
    }

    public static function getRandom(): self
    {
        return match(rand(1, 3)) {
            1 => self::DRAFT,
            2 => self::PUBLISHED,
            3 => self::INACTIVE,
        };
    }
}
