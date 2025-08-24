<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\LazyLoadingOptimizations;

class AgentProfile extends Model
{
    use HasFactory, LazyLoadingOptimizations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bio',
        'specialization',
        'experience_years',
        'languages',
        'verification_status',
        'rating',
        'completed_transactions',
        'id_document_path',
        'business_document_path',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'languages' => 'array',
    ];

    /**
     * Get the user that owns the agent profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service requests assigned to this agent.
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'agent_id');
    }

    /**
     * Get the marketplace listings managed by this agent.
     */
    public function marketplaceListings()
    {
        return $this->hasMany(MarketplaceListing::class, 'agent_id');
    }

    /**
     * Get the reviews for this agent.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'agent_id');
    }

    /**
     * Scope a query to only include verified agents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope a query to only include pending agents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Check if the agent profile is verified.
     *
     * @return bool
     */
    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Check if the agent profile is pending verification.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Check if the agent profile was rejected.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->verification_status === 'rejected';
    }

    /**
     * Get the ID document URL.
     *
     * @return string|null
     */
    public function getIdDocumentUrlAttribute()
    {
        return $this->id_document_path ? asset('storage/' . $this->id_document_path) : null;
    }

    /**
     * Get the business document URL.
     *
     * @return string|null
     */
    public function getBusinessDocumentUrlAttribute()
    {
        return $this->business_document_path ? asset('storage/' . $this->business_document_path) : null;
    }
}