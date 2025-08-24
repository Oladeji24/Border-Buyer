<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reviewer_id',
        'reviewee_id',
        'agent_id',
        'service_request_id',
        'marketplace_listing_id',
        'transaction_id',
        'rating',
        'comment',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get the user who wrote the review.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the user who was reviewed.
     */
    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    /**
     * Get the agent who was reviewed.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the service request associated with the review.
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the marketplace listing associated with the review.
     */
    public function marketplaceListing()
    {
        return $this->belongsTo(MarketplaceListing::class);
    }

    /**
     * Get the transaction associated with the review.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}