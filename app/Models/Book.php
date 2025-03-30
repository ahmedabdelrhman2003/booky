<?php

namespace App\Models;

use App\Enums\BookStatusEnum;
use App\Observers\BookObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy([BookObserver::class])]
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

    protected $casts = [
        'status' => BookStatusEnum::class,
    ];

    protected static function booted()
    {
        static::saving(function ($book) {
            $book->price = $book->price_before_commission + $book->price_before_commission * 0.10;
            $book->status = BookStatusEnum::PENDING;
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

    public function scopeActive(Builder $query): void
    {
        $query->where('activation', 1);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('book-cover');
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('status', BookStatusEnum::APPROVED->value);
    }

}
