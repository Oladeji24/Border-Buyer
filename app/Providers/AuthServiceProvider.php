<?php

namespace App\Providers;

use App\Models\AgentProfile;
use App\Models\MarketplaceListing;
use App\Models\ServiceRequest;
use App\Policies\AgentProfilePolicy;
use App\Policies\MarketplaceListingPolicy;
use App\Policies\ServiceRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        AgentProfile::class => AgentProfilePolicy::class,
        MarketplaceListing::class => MarketplaceListingPolicy::class,
        ServiceRequest::class => ServiceRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define admin gate
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        // Define agent gate
        Gate::define('agent', function ($user) {
            return $user->role === 'agent';
        });

        // Define buyer gate
        Gate::define('buyer', function ($user) {
            return $user->role === 'buyer';
        });

        // Define seller gate
        Gate::define('seller', function ($user) {
            return $user->role === 'seller';
        });
    }
}
