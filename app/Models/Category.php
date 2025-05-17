<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'status'
    ];

    /**
     * Relationship to Book Model
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books (): HasMany {
        return $this->hasMany(Book::class, 'category_id');
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

    /**
     * Execute when make some action with model
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            $category->books()->delete();
        });
    }
}
