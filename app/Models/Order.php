<?php

namespace App\Models;

use App\Enums\BookStatusEnum;
use App\Enums\OrderTypesEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;


class Order extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = [
        'user_id',
        'book_id',
        'price',
        'status',
        'order_id',
        'webhook_response',
        'rate'
    ];

    protected $casts = [
        'status' => OrderTypesEnum::class,
        'price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function scopePaid(Builder $query): void
    {
        $query->where('status', OrderTypesEnum::PAID->value);
    }
}
