<?php

namespace Weboccult\LaravelAwsCloudWatchLogger;

use Weboccult\LaravelAwsCloudWatchLogger\Commands\CloudWatchConfigPublishCommand;
use Illuminate\Support\ServiceProvider;

class LaravelAwsCloudWatchLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/cloudwatch.php' => config_path('cloudwatch.php'),
            ], 'cloudwatch-config');
            // Registering package commands.
            $this->commands([
                CloudWatchConfigPublishCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/cloudwatch.php', 'cloudwatch');
        // Register the main class to use with the facade
        $this->app->singleton('laravel-aws-cloud-watch-logger', function () {
            return new LaravelAwsCloudWatchLogger;
        });
    }
}
