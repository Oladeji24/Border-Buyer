<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Providers\AppServiceProvider;

class QueryOptimizationService
{
    /**
     * Execute a query with caching and optimization
     */
    public function cachedQuery(
        string $cacheKey,
        Builder $query,
        int $ttl = null,
        array $tags = [],
        bool $forceRefresh = false
    ) {
        $ttl = $ttl ?? AppServiceProvider::CACHE_TTL_MEDIUM;

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, $ttl, function () use ($query) {
            return $this->optimizeQuery($query)->get();
        });
    }

    /**
     * Execute a paginated query with caching
     */
    public function cachedPaginatedQuery(
        string $cacheKey,
        Builder $query,
        int $perPage = 15,
        int $ttl = null,
        array $tags = [],
        bool $forceRefresh = false
    ) {
        $ttl = $ttl ?? AppServiceProvider::CACHE_TTL_MEDIUM;
        $page = request()->get('page', 1);

        $paginatedKey = "{$cacheKey}_page_{$page}_per_{$perPage}";

        if ($forceRefresh) {
            Cache::forget($paginatedKey);
        }

        return Cache::remember($paginatedKey, $ttl, function () use ($query, $perPage) {
            return $this->optimizeQuery($query)->paginate($perPage);
        });
    }

    /**
     * Optimize a query for performance
     */
    public function optimizeQuery(Builder $query): Builder
    {
        return $query
            ->when(config('app.debug') === false, function ($q) {
                // Only select necessary columns in production
                return $q->select($this->getOptimizedSelectColumns($q->getModel()));
            })
            ->with($this->getDefaultEagerLoads($query->getModel()))
            ->when($this->shouldUseIndex(), function ($q) {
                return $this->applyIndexHints($q);
            });
    }

    /**
     * Get optimized select columns for a model
     */
    protected function getOptimizedSelectColumns($model): array
    {
        $modelClass = get_class($model);
        
        $optimizedColumns = [
            \App\Models\User::class => [
                'id', 'name', 'email', 'role', 'country', 'phone', 'profile_image', 'created_at'
            ],
            \App\Models\AgentProfile::class => [
                'id', 'user_id', 'bio', 'specialization', 'experience_years', 
                'verification_status', 'rating', 'completed_transactions'
            ],
            \App\Models\MarketplaceListing::class => [
                'id', 'seller_id', 'agent_id', 'title', 'description', 'category',
                'price', 'country_from', 'country_to', 'status', 'expires_at', 'created_at'
            ],
            \App\Models\ServiceRequest::class => [
                'id', 'buyer_id', 'agent_id', 'title', 'description', 'status',
                'budget', 'deadline', 'created_at'
            ],
        ];

        return $optimizedColumns[$modelClass] ?? ['*'];
    }

    /**
     * Get default eager loads for a model
     */
    protected function getDefaultEagerLoads($model): array
    {
        $modelClass = get_class($model);
        
        $defaultEagerLoads = [
            \App\Models\User::class => ['agentProfile'],
            \App\Models\AgentProfile::class => ['user'],
            \App\Models\MarketplaceListing::class => ['seller', 'agent'],
            \App\Models\ServiceRequest::class => ['user', 'agent'],
        ];

        return $defaultEagerLoads[$modelClass] ?? [];
    }

    /**
     * Check if we should use database indexes
     */
    protected function shouldUseIndex(): bool
    {
        return config('app.env') === 'production' && DB::connection()->getDriverName() === 'mysql';
    }

    /**
     * Apply index hints for MySQL
     */
    protected function applyIndexHints(Builder $query): Builder
    {
        $model = $query->getModel();
        $modelClass = get_class($model);

        $indexHints = [
            \App\Models\User::class => 'FORCE INDEX (users_email_index)',
            \App\Models\AgentProfile::class => 'FORCE INDEX (agent_profiles_user_id_index)',
            \App\Models\MarketplaceListing::class => 'FORCE INDEX (marketplace_listings_status_index)',
        ];

        if (isset($indexHints[$modelClass])) {
            $query->getQuery()->from = DB::raw(
                "{$model->getTable()} {$indexHints[$modelClass]}"
            );
        }

        return $query;
    }

    /**
     * Execute a raw query with performance monitoring
     */
    public function monitoredRawQuery(string $sql, array $bindings = [], int $timeout = 5000)
    {
        $start = microtime(true);
        
        $result = DB::select($sql, $bindings);
        
        $executionTime = (microtime(true) - $start) * 1000;
        
        if ($executionTime > $timeout) {
            logger()->warning('Slow raw query detected', [
                'sql' => $sql,
                'execution_time' => $executionTime,
                'bindings' => $bindings
            ]);
        }

        return $result;
    }

    /**
     * Clear cache for specific model or tags
     */
    public function clearCache($model = null, array $tags = [])
    {
        if ($model) {
            $modelClass = get_class($model);
            $tags = array_merge($tags, $this->getCacheTagsForModel($modelClass));
        }

        if (!empty($tags) && Cache::supportsTags()) {
            Cache::tags($tags)->flush();
        }
    }

    /**
     * Get cache tags for a model class
     */
    protected function getCacheTagsForModel(string $modelClass): array
    {
        $modelToTags = [
            \App\Models\User::class => ['users'],
            \App\Models\AgentProfile::class => ['agents', 'profiles'],
            \App\Models\MarketplaceListing::class => ['listings', 'marketplace'],
            \App\Models\ServiceRequest::class => ['services', 'requests'],
        ];

        return $modelToTags[$modelClass] ?? [];
    }

    /**
     * Preload data for better performance
     */
    public function preloadData(array $config)
    {
        foreach ($config as $cacheKey => $preloadConfig) {
            $query = $preloadConfig['query'];
            $ttl = $preloadConfig['ttl'] ?? AppServiceProvider::CACHE_TTL_LONG;
            $tags = $preloadConfig['tags'] ?? [];

            Cache::remember($cacheKey, $ttl, function () use ($query) {
                return $query->get();
            });
        }
    }
}
