<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;
    protected $fillable = ['author_id', 'amount', 'status'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
