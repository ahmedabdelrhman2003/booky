<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum  SocialTypesEnum: string
{
    case FACEBOOK = 'facebook';
    case GOOGLE = 'google';

}
