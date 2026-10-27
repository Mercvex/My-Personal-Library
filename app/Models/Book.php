<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'author',
        'category',
        'status',
        'cover_image',
        'current_page',
        'summary',
        'is_favorite',
    ];

    protected function casts(): array
    {
        return [
            'current_page' => 'integer',
            'is_favorite' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
