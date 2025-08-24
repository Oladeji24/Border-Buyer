<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Traits\LazyLoadingOptimizations;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, LazyLoadingOptimizations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'country',
        'phone',
        'profile_image',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get the agent profile associated with the user.
     */
    public function agentProfile()
    {
        return $this->hasOne(AgentProfile::class);
    }

    /**
     * Get the service requests created by the user.
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'buyer_id');
    }

    /**
     * Get the marketplace listings created by the user.
     */
    public function marketplaceListings()
    {
        return $this->hasMany(MarketplaceListing::class, 'seller_id');
    }

    /**
     * Get the transactions associated with the user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the reviews written by the user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    /**
     * Get the reviews received by the user.
     */
    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an agent
     */
    public function isAgent()
    {
        return $this->role === 'agent';
    }

    /**
     * Check if user is a buyer
     */
    public function isBuyer()
    {
        return $this->role === 'buyer';
    }

    /**
     * Check if user is a seller
     */
    public function isSeller()
    {
        return $this->role === 'seller';
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->is_active ?? true;
    }

    /**
     * Get the user's full name with proper formatting
     */
    public function getFullNameAttribute()
    {
        return ucwords($this->name);
    }

    /**
     * Get the user's profile image URL with fallback
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        
        // Generate avatar based on initials
        $name = $this->name;
        $initials = '';
        $words = explode(' ', $name);
        
        if (count($words) >= 2) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
            $initials = strtoupper(substr($name, 0, 2));
        }
        
        return "https://ui-avatars.com/api/?name={$initials}&background=3B82F6&color=fff&size=200";
    }

    /**
     * Get the user's display role with proper formatting
     */
    public function getDisplayRoleAttribute()
    {
        return ucfirst($this->role);
    }

    /**
     * Scope a query to only include active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include users with a specific role
     */
    public function scopeWithRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope a query to only include verified users
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Update last login information
     */
    public function updateLastLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    /**
     * Check if user can access admin panel
     */
    public function canAccessAdminPanel()
    {
        return $this->isAdmin() && $this->isActive();
    }

    /**
     * Get the user's dashboard route based on role
     */
    public function getDashboardRoute()
    {
        if ($this->isAdmin()) {
            return 'admin.dashboard';
        }
        
        if ($this->isAgent()) {
            return 'agent.profile.show';
        }
        
        return 'home';
    }
}
