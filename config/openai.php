<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization (Now supports OpenRouter)
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI/OpenRouter API Key and organization. 
    | For OpenRouter, only the API key is needed.
    | OpenRouter API keys: https://openrouter.ai/keys
    |
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Request Settings
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default model, timeout and other request settings
    | that will be used when making requests to the OpenAI API or OpenRouter.
    | For DeepSeek via OpenRouter, use: deepseek/deepseek-chat-v3-0324:free
    |
    */

    'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
    'max_tokens' => env('OPENAI_MAX_TOKENS', 1500),
    'temperature' => env('OPENAI_TEMPERATURE', 0.7),
    'timeout' => env('OPENAI_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Content Generation Settings
    |--------------------------------------------------------------------------
    |
    | These settings control the behavior of content generation for different
    | types of business documents.
    |
    */

    'email' => [
        'model' => env('OPENAI_EMAIL_MODEL', 'gpt-4o-mini'),
        'max_tokens' => env('OPENAI_EMAIL_MAX_TOKENS', 1500),
        'temperature' => env('OPENAI_EMAIL_TEMPERATURE', 0.7),
        'cache_duration' => env('OPENAI_EMAIL_CACHE_DURATION', 3600), // 1 hour
    ],

    'quotation' => [
        'model' => env('OPENAI_QUOTATION_MODEL', 'gpt-4o-mini'),
        'max_tokens' => env('OPENAI_QUOTATION_MAX_TOKENS', 2000),
        'temperature' => env('OPENAI_QUOTATION_TEMPERATURE', 0.5),
        'cache_duration' => env('OPENAI_QUOTATION_CACHE_DURATION', 7200), // 2 hours
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Settings
    |--------------------------------------------------------------------------
    |
    | These settings control what happens when the OpenAI API is unavailable
    | or returns an error.
    |
    */

    'fallback_enabled' => env('OPENAI_FALLBACK_ENABLED', true),
    'log_errors' => env('OPENAI_LOG_ERRORS', true),
    'retry_attempts' => env('OPENAI_RETRY_ATTEMPTS', 2),
];
