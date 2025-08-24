<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'buyer_id',
        'agent_id',
        'title',
        'description',
        'category',
        'budget',
        'country_from',
        'country_to',
        'status',
        'deadline',
        'product_images',
        'additional_documents',
        'inspection_report',
        'inspection_photos',
        'tracking_number',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'datetime',
        'product_images' => 'array',
        'additional_documents' => 'array',
        'inspection_photos' => 'array',
    ];

    /**
     * The possible status values for a service request.
     *
     * @var array
     */
    public static $statuses = [
        'order' => 'Order Placed',
        'inspection' => 'Inspection in Progress',
        'shipping' => 'Shipping',
        'delivery' => 'Out for Delivery',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    /**
     * Get the buyer who created the service request.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the agent assigned to the service request.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the transactions associated with the service request.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the reviews for the service request.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope a query to only include service requests with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include service requests that need inspection.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeedsInspection($query)
    {
        return $query->where('status', 'order');
    }

    /**
     * Scope a query to only include service requests that are in progress.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['inspection', 'shipping', 'delivery']);
    }

    /**
     * Check if the service request is in "order" status.
     *
     * @return bool
     */
    public function isOrder()
    {
        return $this->status === 'order';
    }

    /**
     * Check if the service request is in "inspection" status.
     *
     * @return bool
     */
    public function isInspection()
    {
        return $this->status === 'inspection';
    }

    /**
     * Check if the service request is in "shipping" status.
     *
     * @return bool
     */
    public function isShipping()
    {
        return $this->status === 'shipping';
    }

    /**
     * Check if the service request is in "delivery" status.
     *
     * @return bool
     */
    public function isDelivery()
    {
        return $this->status === 'delivery';
    }

    /**
     * Check if the service request is completed.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the service request is cancelled.
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get the status label with appropriate styling.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'order' => '<span class="badge bg-info">Order Placed</span>',
            'inspection' => '<span class="badge bg-primary">Inspection in Progress</span>',
            'shipping' => '<span class="badge bg-warning">Shipping</span>',
            'delivery' => '<span class="badge bg-info">Out for Delivery</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
        ];

        return $labels[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get the product image URLs.
     *
     * @return array
     */
    public function getProductImageUrlsAttribute()
    {
        if (!$this->product_images) {
            return [];
        }

        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->product_images);
    }

    /**
     * Get the additional document URLs.
     *
     * @return array
     */
    public function getAdditionalDocumentUrlsAttribute()
    {
        if (!$this->additional_documents) {
            return [];
        }

        return array_map(function ($document) {
            return asset('storage/' . $document);
        }, $this->additional_documents);
    }

    /**
     * Get the inspection photo URLs.
     *
     * @return array
     */
    public function getInspectionPhotoUrlsAttribute()
    {
        if (!$this->inspection_photos) {
            return [];
        }

        return array_map(function ($photo) {
            return asset('storage/' . $photo);
        }, $this->inspection_photos);
    }

    /**
     * Move the service request to the next status in the workflow.
     *
     * @return bool
     */
    public function moveToNextStatus()
    {
        $workflow = [
            'order' => 'inspection',
            'inspection' => 'shipping',
            'shipping' => 'delivery',
            'delivery' => 'completed',
        ];

        if (isset($workflow[$this->status])) {
            $this->status = $workflow[$this->status];
            return $this->save();
        }

        return false;
    }
}