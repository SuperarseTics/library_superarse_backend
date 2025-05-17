<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'code',
        'title',
        'cover',
        'author',
        'publication',
        'synopsis',
        'edition',
        'stock',
        'status'
    ];

    /**
     * Relationship to Category Model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category (): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relationship to Booking Model
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings(): HasMany {
        return $this->hasMany(Booking::class, 'book_id');
    }

    /**
     * Scope to filter by category relationship
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $fCategory
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByCategory(Builder $query, ?string $fCategory): Builder
    {
        return $query->when($fCategory, function ($q) use ($fCategory) {
            $q->whereHas('category', function ($sq) use ($fCategory) {
                $sq->where('title', $fCategory);
            });
        });
    }

    /**
     * Scope to filter by author column
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $fAuthor
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByAuthor(Builder $query, ?string $fAuthor): Builder
    {
        return $query->when($fAuthor, fn($q) => $q->where('author', $fAuthor));
    }

    /**
     * Scope to filter by title column
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $fTitle
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByTitle(Builder $query, ?string $fTitle): Builder
    {
        return $query->when($fTitle, fn($q) => $q->where('title', "like", "%".$fTitle."%"));
    }

    /**
     * Scope to filter by publication column
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $fPublication
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByPublication(Builder $query, ?int $fPublication): Builder
    {
        return $query->when($fPublication, fn($q) => $q->where('publication', $fPublication));
    }

    /**
     * Scope to filter by status active
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function getCategoryTitleAttribute()
    {
        return $this->category->title;
    }
}
