<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'service_request_id',
        'marketplace_listing_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'transaction_reference',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user associated with the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service request associated with the transaction.
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the marketplace listing associated with the transaction.
     */
    public function marketplaceListing()
    {
        return $this->belongsTo(MarketplaceListing::class);
    }

    /**
     * Get the reviews for the transaction.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}