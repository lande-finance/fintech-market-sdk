<?php

namespace Hashstudio\FintechMarketSdk;

use Illuminate\Support\ServiceProvider;

class FintechMarketSdkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('fintech-market-sdk.php'),
            ], 'fintech-market-config');

        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'fintech-market-sdk');

        // Register the main class to use with the facade
        $this->app->singleton(FintechMarketSdk::class, function ($app) {
            $config = $app->make('config')->get('fintech-market-sdk');

            return new FintechMarketSdk(
                $config['credentials']['client_id'],
                $config['credentials']['client_secret'],
                $config['credentials']['organization'],
                $config['debug']
            );
        });
    }
}
