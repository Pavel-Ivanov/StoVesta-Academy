<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case DRAFT = 'draft';        // Черновик
    case REVIEW = 'review';      // На проверке
    case PUBLISHED = 'published';// Готово к публикации
    case ARCHIVED = 'archived';  // Архив

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Черновик',
            self::REVIEW => 'На проверке',
            self::PUBLISHED => 'Готово',
            self::ARCHIVED => 'Архив',
        };
    }

    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->label();
        }
        return $out;
    }
}
