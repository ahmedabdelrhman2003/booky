<?php

namespace App\Models;

use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Author extends Authenticatable implements HasMedia,FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable,InteractsWithMedia;

    public function canAccessPanel(Panel $panel): bool
    {
        return true ;
    }

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'bio',
        'iban',
        'wallet'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('author-image')->useDisk('s3')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(50)
                    ->height(50);
            });
    }

    public function scopeHasActiveBooks($query)
    {
        $query->whereHas('books', fn($q) => $q->active()->approved());
    }
}
