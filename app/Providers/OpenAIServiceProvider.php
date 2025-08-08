<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenAI\Laravel\Facades\OpenAI;
use App\Services\OpenAIService;

class OpenAIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register OpenAI Service as singleton
        $this->app->singleton(OpenAIService::class, function ($app) {
            return new OpenAIService();
        });

        // Merge OpenAI configuration
        $this->mergeConfigFrom(
            __DIR__.'/../../config/openai.php', 'openai'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configure OpenAI client if API key is available
        if (config('openai.api_key') && config('openai.api_key') !== 'your_openai_api_key_here') {
            // Set default configuration for OpenAI client
            config([
                'openai.api_key' => config('openai.api_key'),
                'openai.organization' => config('openai.organization'),
            ]);
        }

        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/openai.php' => config_path('openai.php'),
            ], 'openai-config');
        }
    }
}
