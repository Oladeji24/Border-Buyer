<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    | Supported: "apc", "array", "database", "file",
    |            "memcached", "redis", "dynamodb"
    |
    */

    'default' => env('CACHE_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    */

    'stores' => [

        'apc' => [
            'driver' => 'apc',
        ],

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
            'lock_connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache'),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL Settings
    |--------------------------------------------------------------------------
    |
    | Custom TTL settings for different types of cached data
    |
    */

    'ttl' => [
        'listings' => env('CACHE_TTL_LISTINGS', 3600), // 1 hour
        'agents' => env('CACHE_TTL_AGENTS', 7200), // 2 hours
        'dashboard' => env('CACHE_TTL_DASHBOARD', 1800), // 30 minutes
        'search' => env('CACHE_TTL_SEARCH', 900), // 15 minutes
        'directory' => env('CACHE_TTL_DIRECTORY', 3600), // 1 hour
        'default' => env('CACHE_TTL_DEFAULT', 1800), // 30 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Fallback Settings
    |--------------------------------------------------------------------------
    |
    | Settings for cache fallback behavior when cache fails
    |
    */

    'fallback' => [
        'enabled' => env('CACHE_FALLBACK_ENABLED', true),
        'log_errors' => env('CACHE_FALLBACK_LOG_ERRORS', true),
        'retry_attempts' => env('CACHE_FALLBACK_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('CACHE_FALLBACK_RETRY_DELAY', 100), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Compression
    |--------------------------------------------------------------------------
    |
    | Enable compression for cached data to reduce memory usage
    |
    */

    'compression' => [
        'enabled' => env('CACHE_COMPRESSION_ENABLED', false),
        'threshold' => env('CACHE_COMPRESSION_THRESHOLD', 1024), // bytes
    ],

];
