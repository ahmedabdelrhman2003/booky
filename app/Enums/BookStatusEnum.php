<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum  BookStatusEnum: string implements HasLabel
{
 case PENDING = 'pending';
 case APPROVED = 'approved';
 case REJECTED = 'rejected';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::REJECTED => 'warning',
            self::APPROVED => 'success',
        };
    }
}
