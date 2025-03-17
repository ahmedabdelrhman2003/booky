<?php

namespace App\Enums;

enum OTPActions : int
{
    case VERIFY_EMAIL = 1;
    case CHANGE_EMAIL = 2;
    case RESET_PASSWORD = 3;

    public function label(): string
    {
        return match($this) {
            self::VERIFY_EMAIL => 'verify email',
            self::CHANGE_EMAIL => 'change email',
            self::RESET_PASSWORD => 'reset password',
        };
    }

}
