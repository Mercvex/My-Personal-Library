<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $author
 * @property string|null $category
 * @property string $status
 * @property string|null $cover_image
 * @property string|null $book_file_path
 * @property string|null $original_filename
 * @property int|null $current_page
 * @property int|null $total_pages
 * @property string|null $summary
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
        'book_file_path',
        'original_filename',
        'current_page',
        'total_pages',
        'summary',
    ];

    protected function casts(): array
    {
        return [
            'current_page' => 'integer',
            'total_pages'  => 'integer',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Users who have favorited this book. @return BelongsToMany<User, $this> */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
