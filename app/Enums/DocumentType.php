<?php

namespace App\Enums;

enum DocumentType: string
{
    case CERTIFICATE = 'certificate';   // Сертификаты
    case FORM        = 'form';          // Формы
    case HANDBOOK    = 'handbook';      // Справочник

    public function label(): string
    {
        return match ($this) {
            self::CERTIFICATE => 'Сертификаты',
            self::FORM        => 'Формы',
            self::HANDBOOK    => 'Справочник',
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
