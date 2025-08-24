<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Laravel's queue API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'sync'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    |
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
            'after_commit' => false,
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => env('BEANSTALKD_HOST', 'localhost'),
            'queue' => env('BEANSTALKD_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => 0,
            'after_commit' => false,
        ],

        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'default'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_QUEUE_CONNECTION', 'default'),
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
            'after_commit' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Worker Configuration
    |--------------------------------------------------------------------------
    |
    | Production-ready queue worker settings for optimal performance
    |
    */

    'worker' => [
        'sleep' => env('QUEUE_WORKER_SLEEP', 3),
        'timeout' => env('QUEUE_WORKER_TIMEOUT', 60),
        'tries' => env('QUEUE_WORKER_TRIES', 3),
        'memory' => env('QUEUE_WORKER_MEMORY', 128),
        'delay' => env('QUEUE_WORKER_DELAY', 0),
        'force' => env('QUEUE_WORKER_FORCE', false),
        'stop_when_empty' => env('QUEUE_WORKER_STOP_WHEN_EMPTY', false),
        'max_jobs' => env('QUEUE_WORKER_MAX_JOBS', 0),
        'max_time' => env('QUEUE_WORKER_MAX_TIME', 0),
        'rest' => env('QUEUE_WORKER_REST', 0),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Monitoring
    |--------------------------------------------------------------------------
    |
    | Configuration for queue monitoring and alerting
    |
    */

    'monitoring' => [
        'enabled' => env('QUEUE_MONITORING_ENABLED', true),
        'threshold' => env('QUEUE_MONITORING_THRESHOLD', 100), // Alert if queue size exceeds this
        'check_interval' => env('QUEUE_MONITORING_INTERVAL', 300), // Check every 5 minutes
        'notification_channels' => env('QUEUE_MONITORING_CHANNELS', 'log'), // log, email, slack
    ],

];
