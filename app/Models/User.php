<?php

namespace App\Models;

use App\Enums\MediaTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, HasApiTokens, softDeletes, InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'password',
        'email',
        'social_type',
        'social_id'
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

    protected $appends = [
        'account_verified',
        'is_social'
    ];

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaTypes::AUTHOR_PICTURE->value)->singleFile();
    }

    public function getAccountVerifiedAttribute(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function getIsSocialAttribute(): bool
    {
        return $this->password === null;
    }

    public function favBooks(): MorphToMany
    {
        return $this->morphedByMany(Book::class, 'favourable')->where('favourable_type', Book::class)->withTimestamps();
    }


}
