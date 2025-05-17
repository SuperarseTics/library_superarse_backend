<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'book_id',
        'booking_date',
        'delivery_date',
        'giveback_date',
        'last_giveback_date',
        'status'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'datetime',
            'delivery_date' => 'datetime',
            'giveback_date' => 'datetime',
            'last_giveback_date' => 'datetime'
        ];
    }

    /**
     * Relationship to User Model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship to Book Model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book(): BelongsTo {
        return $this->belongsTo(Book::class, 'book_id');
    }

    /**
     * Scope to filter by book category relationship
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $fUser
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByUser(Builder $query, int $fUser): Builder
    {
        return $query->where('user_id', $fUser);
    }

    /**
     * Scope to filter by book category relationship
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $fCategory
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByBookCategory(Builder $query, ?string $fCategory): Builder
    {
        return $query->when($fCategory, function ($q) use ($fCategory) {
            $q->whereHas('book', function ($sq) use ($fCategory) {
                $sq->whereHas('category', function ($sqa) use ($fCategory) {
                    $sqa->where('title', $fCategory);
                });
            });
        });
    }

    /**
     * Scope to filter by book code relationship
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $fCode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByBookCode(Builder $query, ?string $fCode): Builder
    {
        return $query->when($fCode, function ($q) use ($fCode) {
            $q->whereHas('book', function ($sq) use ($fCode) {
                $sq->where('code', $fCode);
            });
        });
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
        static::creating(function ($booking) {
            $booking->uuid = (string) Str::uuid();
            $booking->user_id = Auth::id();
            $booking->status = config('constants.states.reserve');
        });
    }
}
