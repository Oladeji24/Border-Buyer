<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait LazyLoadingOptimizations
{
    /**
     * Boot the trait
     */
    protected static function bootLazyLoadingOptimizations()
    {
        static::addGlobalScope('optimized_loading', function (Builder $builder) {
            // Apply default eager loading optimizations
            static::applyDefaultEagerLoads($builder);
        });
    }

    /**
     * Apply default eager loads based on model type
     */
    protected static function applyDefaultEagerLoads(Builder $builder)
    {
        $modelClass = static::class;
        
        $defaultEagerLoads = [
            \App\Models\User::class => ['agentProfile'],
            \App\Models\AgentProfile::class => ['user'],
            \App\Models\MarketplaceListing::class => ['seller', 'agent'],
            \App\Models\ServiceRequest::class => ['user', 'agent'],
            \App\Models\Transaction::class => ['buyer', 'seller', 'service'],
            \App\Models\Review::class => ['reviewer', 'reviewee'],
        ];

        if (isset($defaultEagerLoads[$modelClass])) {
            $builder->with($defaultEagerLoads[$modelClass]);
        }
    }

    /**
     * Scope to load minimal relationships for performance
     */
    public function scopeMinimal(Builder $query): Builder
    {
        return $query->withoutGlobalScope('optimized_loading')
            ->select($this->getTable() . '.*');
    }

    /**
     * Scope to load all relationships (use with caution)
     */
    public function scopeWithAll(Builder $query): Builder
    {
        $modelClass = static::class;
        
        $allRelations = [
            \App\Models\User::class => ['agentProfile', 'serviceRequests', 'marketplaceListings', 'transactions', 'reviews', 'receivedReviews'],
            \App\Models\AgentProfile::class => ['user', 'serviceRequests', 'marketplaceListings', 'reviews'],
            \App\Models\MarketplaceListing::class => ['seller', 'agent', 'transactions'],
            \App\Models\ServiceRequest::class => ['user', 'agent', 'transactions', 'reviews'],
            \App\Models\Transaction::class => ['buyer', 'seller', 'service', 'reviews'],
            \App\Models\Review::class => ['reviewer', 'reviewee', 'transaction'],
        ];

        if (isset($allRelations[$modelClass])) {
            return $query->with($allRelations[$modelClass]);
        }

        return $query;
    }

    /**
     * Lazy load a relationship only when accessed
     */
    public function lazyLoad(string $relation)
    {
        if (!$this->relationLoaded($relation)) {
            $this->load($relation);
        }
        
        return $this->getRelation($relation);
    }

    /**
     * Load multiple relationships lazily
     */
    public function lazyLoadMany(array $relations)
    {
        $relationsToLoad = array_filter($relations, function ($relation) {
            return !$this->relationLoaded($relation);
        });

        if (!empty($relationsToLoad)) {
            $this->load($relationsToLoad);
        }

        return $this;
    }

    /**
     * Check if a relationship is loaded without triggering loading
     */
    public function isRelationLoaded(string $relation): bool
    {
        return $this->relationLoaded($relation);
    }

    /**
     * Get the count of a relationship without loading the actual data
     */
    public function getRelationCount(string $relation): int
    {
        return $this->{$relation}()->count();
    }

    /**
     * Custom eager loading with callback for complex relationships
     */
    public function scopeWithOptimized(Builder $query, array $relations): Builder
    {
        foreach ($relations as $relation => $callback) {
            if (is_callable($callback)) {
                $query->with([$relation => $callback]);
            } else {
                $query->with($relation);
            }
        }

        return $query;
    }

    /**
     * Prevent N+1 queries by ensuring relationships are loaded
     */
    public static function preventNPlusOne(Builder $query, array $requiredRelations = []): Builder
    {
        $modelClass = static::class;
        
        $defaultPreventions = [
            \App\Models\User::class => ['agentProfile'],
            \App\Models\AgentProfile::class => ['user'],
            \App\Models\MarketplaceListing::class => ['seller'],
            \App\Models\ServiceRequest::class => ['user'],
        ];

        $relationsToLoad = array_unique(array_merge(
            $defaultPreventions[$modelClass] ?? [],
            $requiredRelations
        ));

        return $query->with($relationsToLoad);
    }
}
