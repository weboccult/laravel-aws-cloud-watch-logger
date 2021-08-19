<?php
if (!function_exists('laravelAwsCloudWatchLogger')) {
    /**
     * Access laravelAwsCloudwatchLogger through helper.
     * @return Weboccult\LaravelAwsCloudWatchLogger\Facades\LaravelAwsCloudWatchLogger
     */
    function laravelAwsCloudwatchLogger()
    {
        return app('laravel-aws-cloud-watch-logger');
    }
}
