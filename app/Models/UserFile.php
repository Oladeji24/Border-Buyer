<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class UserFile extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'path',
        'mime_type',
        'size',
        'type',
        'category',
        'description',
        'hash',
        'is_processed',
        'metadata',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'size' => 'integer',
        'is_processed' => 'boolean',
        'metadata' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
        'thumbnail_url',
        'file_size_human',
        'is_expired',
    ];

    /**
     * Get the user that owns the file.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the file URL.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }

    /**
     * Get the thumbnail URL (for images).
     *
     * @return string|null
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->type === 'image') {
            // Generate thumbnail path
            $thumbnailPath = dirname($this->path) . '/thumbnails/' . basename($this->path);
            
            if (Storage::exists($thumbnailPath)) {
                return Storage::url($thumbnailPath);
            }
            
            // Return original image if thumbnail doesn't exist
            return $this->url;
        }

        return null;
    }

    /**
     * Get the human-readable file size.
     *
     * @return string
     */
    public function getFileSizeHumanAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = $this->size;
        
        if ($bytes > 0) {
            $unit = intval(floor(log($bytes, 1024)));
            $bytes = $bytes / pow(1024, $unit);
            
            return round($bytes, 2) . ' ' . $units[$unit];
        }

        return '0 B';
    }

    /**
     * Check if the file is expired.
     *
     * @return bool
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Scope a query to only include files of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include files of a given category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include active (non-expired) files.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope a query to only include processed files.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProcessed($query)
    {
        return $query->where('is_processed', true);
    }

    /**
     * Scope a query to only include expired files.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * Get the image dimensions from metadata.
     *
     * @return array|null
     */
    public function getImageDimensionsAttribute()
    {
        if ($this->type === 'image' && $this->metadata && isset($this->metadata['dimensions'])) {
            return $this->metadata['dimensions'];
        }

        return null;
    }

    /**
     * Check if the file is an image.
     *
     * @return bool
     */
    public function isImage()
    {
        return $this->type === 'image';
    }

    /**
     * Check if the file is a document.
     *
     * @return bool
     */
    public function isDocument()
    {
        return $this->type === 'document';
    }

    /**
     * Check if the file is a video.
     *
     * @return bool
     */
    public function isVideo()
    {
        return $this->type === 'video';
    }

    /**
     * Delete the file from storage and the database.
     *
     * @return bool|null
     */
    public function deleteWithStorage()
    {
        // Delete from storage
        Storage::delete($this->path);
        
        // Delete thumbnail if it exists
        if ($this->type === 'image') {
            $thumbnailPath = dirname($this->path) . '/thumbnails/' . basename($this->path);
            Storage::delete($thumbnailPath);
        }
        
        // Delete from database
        return $this->delete();
    }

    /**
     * Get the MIME type icon.
     *
     * @return string
     */
    public function getMimeTypeIconAttribute()
    {
        $iconMap = [
            // Images
            'image/jpeg' => 'fa-file-image',
            'image/png' => 'fa-file-image',
            'image/gif' => 'fa-file-image',
            'image/webp' => 'fa-file-image',
            
            // Documents
            'application/pdf' => 'fa-file-pdf',
            'application/msword' => 'fa-file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fa-file-word',
            'text/plain' => 'fa-file-alt',
            'text/rtf' => 'fa-file-alt',
            
            // Videos
            'video/mp4' => 'fa-file-video',
            'video/avi' => 'fa-file-video',
            'video/quicktime' => 'fa-file-video',
            'video/x-ms-wmv' => 'fa-file-video',
            'video/x-flv' => 'fa-file-video',
        ];

        return $iconMap[$this->mime_type] ?? 'fa-file';
    }

    /**
     * Get the file type color.
     *
     * @return string
     */
    public function getTypeColorAttribute()
    {
        $colorMap = [
            'image' => 'blue',
            'document' => 'green',
            'video' => 'red',
        ];

        return $colorMap[$this->type] ?? 'gray';
    }

    /**
     * Check if file is accessible (not expired and user has permission).
     *
     * @param  \App\Models\User|null  $user
     * @return bool
     */
    public function isAccessible($user = null)
    {
        if ($this->isExpired) {
            return false;
        }

        // Owner can always access
        if ($user && $user->id === $this->user_id) {
            return true;
        }

        // Add additional permission checks here if needed
        // For example: admin access, shared files, etc.

        return false;
    }
}