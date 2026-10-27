<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $author
 * @property string|null $category
 * @property string $status
 * @property string|null $cover_image
 * @property int|null $current_page
 * @property string|null $summary
 * @property bool $is_favorite
 */
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

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
