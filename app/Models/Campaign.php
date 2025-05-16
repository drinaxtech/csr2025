<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'goal_amount',
        'starts_at',
        'ends_at',
        'created_by',
        // Add other relevant fields as needed
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'goal_amount' => 'decimal:2', // Store as decimal with 2 places
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the user that created the campaign.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all the donations made to this campaign.
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function getDonationTotalAttribute()
    {
        return $this->donations()->sum('amount');
    }

    public function getReachedGoalAttribute()
    {
        return $this->donation_total >= $this->goal_amount;
    }

    // You might add methods here to calculate the current total donations,
    // check if the campaign is active, etc.
}