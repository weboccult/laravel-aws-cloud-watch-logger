<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string check()
 * @method static LaravelAwsCloudWatchLogger via(string $driver)
 * @method static LaravelAwsCloudWatchLogger setLogLevel(int $level)
 * @method static LaravelAwsCloudWatchLogger setGroup(string $group)
 * @method static LaravelAwsCloudWatchLogger setStream(string $stream)
 * @method static LaravelAwsCloudWatchLogger setRetentionPeriod(int $days)
 * @method static LaravelAwsCloudWatchLogger setTags(array $tags)
 * @method static LaravelAwsCloudWatchLogger setBatchSize(int $batchSize)
 * @method static LaravelAwsCloudWatchLogger setStore($store)
 * @method static LaravelAwsCloudWatchLogger setTitle(string $title)
 * @method static LaravelAwsCloudWatchLogger setData(array $data)
 * @method static LaravelAwsCloudWatchLogger setModule(string $module)
 * @method static LaravelAwsCloudWatchLogger setOperation(string $operation)
 * @method static void info()
 * @method static void warning()
 * @method static void error()
 * @method static void log()
 * @method static void alert()
 * @method static void critical()
 * @method static void debug()
 * @method static void emergency()
 * @method static void notice()
 * @method static void dispatch(string $type)
 * @mixin \Weboccult\LaravelAwsCloudWatchLogger\LaravelAwsCloudWatchLogger
 * @return \Weboccult\LaravelAwsCloudWatchLogger\LaravelAwsCloudWatchLogger
 * @package \Weboccult\LaravelAwsCloudWatchLogger\LaravelAwsCloudWatchLogger
 */
class LaravelAwsCloudWatchLogger extends Facade
{
    /**
     * Get the registered name of the component.
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-aws-cloud-watch-logger';
    }
}
