<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'client_id',
        'judul',
        'deskripsi',
        'kategori',
        'budget_min',
        'budget_max',
        'batas_lelang',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'budget_min' => 'integer',
            'budget_max' => 'integer',
            'batas_lelang' => 'date',
        ];
    }

    /**
     * The client who posted this project.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * The bids submitted for this project.
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Ratings submitted for this project.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get average rating score rounded to 1 decimal place.
     */
    public function averageRating(): float
    {
        return round((float) ($this->ratings()->avg('skor') ?? 0), 1);
    }

    /**
     * Check if project is currently open for bidding.
     */
    public function isOpen(): bool
    {
        return $this->status === 'terbuka' && $this->batas_lelang->isFuture() || $this->batas_lelang->isToday();
    }

    /**
     * Get the winning bid if any.
     */
    public function winningBid(): ?Bid
    {
        return $this->bids()->where('is_winner', true)->first();
    }
}
