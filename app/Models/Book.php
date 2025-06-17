<?php

namespace App\Models;

use App\Enums\BookStatusEnum;
use App\Enums\OrderTypesEnum;
use App\Observers\BookObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Book extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'author_id',
        'price',
        'price_before_commission',
        'status',
        'activation',
        'pages',
        'language'

    ];



    protected static function booted()
    {
        static::saving(function ($book) {
            $book->price = $book->price_before_commission + $book->price_before_commission * 0.10;
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'books_categories');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activation', 1);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('book-cover')
            ->useDisk('s3');

        $this->addMediaCollection('audio')
            ->useDisk('s3');
    }

    public function favUsers(): MorphToMany
    {
        return $this->morphToMany(User::class, 'favourable')->withTimestamps();
    }

    public function isFavorite(): bool
    {
        return $this->favUsers()->where('user_id', auth('api')->id())->exists();
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', BookStatusEnum::APPROVED->value);
    }

    public function isPurchased(): bool
    {
        return $this->orders()
            ->where('user_id', auth('api')->id())
            ->where('status', OrderTypesEnum::PAID->value)
            ->exists();
    }


    public function scopePurchased(Builder $query): Builder
    {
        return $query->whereHas('orders', function ($query) {
            $query->where('user_id',  auth('api')->id())->paid();
        });
    }

    public function isRated(): bool
    {
        return $this->orders()
            ->where('user_id', auth('api')->id())
            ->where('status', OrderTypesEnum::PAID->value)
            ->whereNotNull('rate')
            ->exists();
    }

    public function rate(): float|null
    {
        return $this->orders()
            ->whereNotNull('rate')
            ->avg('rate');
    }

}
