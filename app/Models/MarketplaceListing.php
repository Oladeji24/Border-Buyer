<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceListing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id',
        'agent_id',
        'title',
        'description',
        'category',
        'price',
        'country_from',
        'country_to',
        'status',
        'is_featured',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the seller who created the marketplace listing.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the agent assigned to the marketplace listing.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the transactions associated with the marketplace listing.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the reviews for the marketplace listing.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}