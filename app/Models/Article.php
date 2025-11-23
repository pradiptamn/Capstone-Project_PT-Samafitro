<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'image',
        'year'
    ];

    /**
     * Relasi: Artikel dimiliki oleh User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}