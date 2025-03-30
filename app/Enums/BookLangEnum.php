<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum  BookLangEnum: string implements HasLabel
{
 case ENGLISH = 'english';
 case ARABIC = 'arabic';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public static function values(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }

}
