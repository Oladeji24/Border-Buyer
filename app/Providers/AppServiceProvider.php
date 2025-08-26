<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ModelChanged;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Cache configuration constants
     */
    const CACHE_TTL_SHORT = 300; // 5 minutes
    const CACHE_TTL_MEDIUM = 1800; // 30 minutes
    const CACHE_TTL_LONG = 86400; // 24 hours
    
    /**
     * Cache key prefixes
     */
    const CACHE_PREFIX_LISTINGS = 'listings_';
    const CACHE_PREFIX_AGENTS = 'agents_';
    const CACHE_PREFIX_STATS = 'stats_';
    const CACHE_PREFIX_USERS = 'users_';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            DB::connection()->getPdo();
            Log::info('Database connection established successfully!');
        } catch (\Exception $e) {
            Log::error('Could not connect to the database: ' . $e->getMessage());
        }

        $this->configureCache();
        $this->registerCacheMacros();
        $this->setupModelObservers();
        $this->setupQueryLogging();
        $this->setupSecurityHeaders();
        $this->setupProductionOptimizations();
    }

    /**
     * Configure cache settings
     */
    protected function configureCache()
    {
        // Set longer cache TTL for production
        if ($this->app->environment('production')) {
            config(['cache.ttl.long' => 604800]); // 7 days for production
        }

        // Configure cache prefix for multi-tenant support if needed
        config(['cache.prefix' => env('CACHE_PREFIX', 'border_buyers_')]);
    }

    /**
     * Register cache macros for common patterns
     */
    protected function registerCacheMacros()
    {
        // Macro for remember with automatic cache busting on model events
        Cache::macro('rememberModel', function ($key, $ttl, $callback, $tags = []) {
            return Cache::tags($tags)->remember($key, $ttl, $callback);
        });

        // Macro for paginated results with cache
        Cache::macro('rememberPaginated', function ($key, $page, $perPage, $ttl, $callback, $tags = []) {
            $paginatedKey = "{$key}_page_{$page}_per_{$perPage}";
            return Cache::tags($tags)->remember($paginatedKey, $ttl, $callback);
        });
    }

    /**
     * Setup model observers for cache invalidation
     */
    protected function setupModelObservers()
    {
        // Listen for model events to invalidate cache
        Event::listen('eloquent.*', function ($eventName, $models) {
            if (is_array($models)) {
                foreach ($models as $model) {
                    $this->invalidateModelCache($model, $eventName);
                }
            } else {
                $this->invalidateModelCache($models, $eventName);
            }
        });

        // Custom event for manual cache invalidation
        Event::listen(ModelChanged::class, function ($event) {
            $this->invalidateCacheByTags($event->tags);
        });
    }

    /**
     * Invalidate cache for a specific model
     */
    protected function invalidateModelCache($model, $eventName)
    {
        $modelClass = get_class($model);
        $tags = $this->getCacheTagsForModel($modelClass);
        
        // Invalidate cache tags related to this model
        if (Cache::supportsTags() && !empty($tags)) {
            Cache::tags($tags)->flush();
        }

        // Log cache invalidation for debugging
        Log::debug("Cache invalidated for model: {$modelClass} due to event: {$eventName}");
    }

    /**
     * Invalidate cache by specific tags
     */
    protected function invalidateCacheByTags(array $tags)
    {
        if (Cache::supportsTags() && !empty($tags)) {
            Cache::tags($tags)->flush();
            Log::debug("Cache invalidated for tags: " . implode(', ', $tags));
        }
    }

    /**
     * Get cache tags for a model class
     */
    protected function getCacheTagsForModel(string $modelClass): array
    {
        $modelToTags = [
            \App\Models\MarketplaceListing::class => ['listings', 'marketplace'],
            \App\Models\AgentProfile::class => ['agents', 'profiles'],
            \App\Models\User::class => ['users'],
            \App\Models\ServiceRequest::class => ['services', 'requests'],
            \App\Models\Transaction::class => ['transactions'],
            \App\Models\Review::class => ['reviews'],
        ];

        return $modelToTags[$modelClass] ?? [];
    }

    /**
     * Setup query logging for performance monitoring
     */
    protected function setupQueryLogging()
    {
        if (config('app.debug')) {
            DB::listen(function ($query) {
                if ($query->time > 100) { // Log slow queries (>100ms)
                    Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                    ]);
                }
            });
        }
    }

    /**
     * Helper method to generate cache key with prefix
     */
    public static function cacheKey(string $prefix, string $identifier): string
    {
        return $prefix . $identifier;
    }

    /**
     * Helper method to get appropriate TTL based on data type
     */
    public static function getTtl(string $dataType): int
    {
        return config("cache.ttl.{$dataType}", config('cache.ttl.default', self::CACHE_TTL_SHORT));
    }

    /**
     * Setup security headers for production
     */
    protected function setupSecurityHeaders()
    {
        if ($this->app->environment('production')) {
            // Add security headers via middleware
            $this->app['router']->pushMiddlewareToGroup('web', \Illuminate\Http\Middleware\TrustProxies::class);
            
            // Configure trusted proxies for Render.com
            if (config('app.env') === 'production') {
                config(['trustedproxy.proxies' => '*']);
                config(['trustedproxy.headers' => [
                    Request::HEADER_X_FORWARDED_FOR,
                    Request::HEADER_X_FORWARDED_HOST,
                    Request::HEADER_X_FORWARDED_PORT,
                    Request::HEADER_X_FORWARDED_PROTO,
                ]]);
            }
        }
    }

    /**
     * Setup production optimizations
     */
    protected function setupProductionOptimizations()
    {
        if ($this->app->environment('production')) {
            // Enable HTTPS forced redirects
            if (request()->secure()) {
                \URL::forceScheme('https');
            }

            // Set strict cookie policies
            config(['session.secure' => true]);
            config(['session.http_only' => true]);
            config(['session.same_site' => 'lax']);

            // Optimize session configuration
            config(['session.lifetime' => 120]);
            config(['session.expire_on_close' => false]);

            // Enable strict password hashing
            config(['auth.providers.users.hash_driver' => 'bcrypt']);
            config(['auth.providers.users.bcrypt.rounds' => 12]);

            // Configure mail settings for production
            config(['mail.default' => 'smtp']);
            config(['mail.encryption' => 'tls']);

            // Set proper file permissions awareness
            umask(0002);
        }
    }
}
