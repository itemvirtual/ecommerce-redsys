<?php

namespace Itemvirtual\EcommerceRedsys;

use Illuminate\Support\ServiceProvider;

class EcommerceRedsysServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('ecommerce-redsys.php'),
            ], 'config');

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'ecommerce-redsys');
        // Add ecommerce-redsys log channel to project
        $this->mergeConfigFrom(__DIR__ . '/../config/log-channel.php', 'logging.channels');
    }
}
