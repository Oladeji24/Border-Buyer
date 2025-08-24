<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Services Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your AI service providers for transaction analysis
    |
    */

    'ai' => [
        // Which AI service to use: dummy, openai, anthropic, google
        'service' => env('AI_SERVICE', 'dummy'),

        // OpenAI Configuration
        'openai_api_key' => env('OPENAI_API_KEY'),
        'openai_model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
        'openai_max_tokens' => env('OPENAI_MAX_TOKENS', 500),
        'openai_temperature' => env('OPENAI_TEMPERATURE', 0.3),

        // Anthropic Claude Configuration
        'anthropic_api_key' => env('ANTHROPIC_API_KEY'),
        'anthropic_model' => env('ANTHROPIC_MODEL', 'claude-3-sonnet-20240229'),
        'anthropic_max_tokens' => env('ANTHROPIC_MAX_TOKENS', 500),
        'anthropic_temperature' => env('ANTHROPIC_TEMPERATURE', 0.3),

        // Google AI Configuration
        'google_api_key' => env('GOOGLE_AI_API_KEY'),
        'google_model' => env('GOOGLE_AI_MODEL', 'gemini-pro'),
        'google_max_tokens' => env('GOOGLE_AI_MAX_TOKENS', 500),
        'google_temperature' => env('GOOGLE_AI_TEMPERATURE', 0.3),

        // General AI Settings
        'fallback_to_dummy' => env('AI_FALLBACK_TO_DUMMY', true),
        'cache_enabled' => env('AI_CACHE_ENABLED', true),
        'cache_ttl' => env('AI_CACHE_TTL', 3600), // 1 hour
        'rate_limit_enabled' => env('AI_RATE_LIMIT_ENABLED', true),
        'rate_limit_attempts' => env('AI_RATE_LIMIT_ATTEMPTS', 10),
        'rate_limit_minutes' => env('AI_RATE_LIMIT_MINUTES', 1),
    ],

];
