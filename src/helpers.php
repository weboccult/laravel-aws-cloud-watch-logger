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
if (!function_exists('laravelAwsCloudWatchLoggerSilent')) {
    /**
     * @param $callback
     */
    function laravelAwsCloudWatchLoggerSilent($callback)
    {
        if ($callback && !is_string($callback) && !is_array($callback) && is_callable($callback)) {
            try {
                $callback();
            }
            catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error($e->getMessage());
            }
        }
    }
}
if (!function_exists('laravelAwsCloudWatchLoggerInline')) {
    /**
     * @param $callback
     */
    function laravelAwsCloudWatchLoggerInline($type, $title, $data, $module, $operation, $model = null, $tags = null, $via = null, $logLevel = null, $group = null, $stream = null, $retentionPeriod = null, $batchSize = null)
    {
        try {
            $logger = laravelAwsCloudwatchLogger();
            if ($via) {
                $logger->via($via);
            }
            $logger->setTitle($title)->setModule($module)->setOperation($operation)->setData($data);
            if ($model) {
                $logger->setModel($model);
            }
            if ($logLevel) {
                $logger->setLogLevel('error');
            }
            if ($tags) {
                $logger->setTags($tags);
            }
            if ($group) {
                $logger->setGroup($group);
            }
            if ($stream) {
                $logger->setStream($stream);
            }
            if ($retentionPeriod) {
                $logger->setRetentionPeriod($retentionPeriod);
            }
            if ($retentionPeriod) {
                $logger->setBatchSize($retentionPeriod);
            }
            if ($batchSize) {
                $logger->setStream($batchSize);
            }
            switch ($type) {
                case  'info' :
                    $logger->info();
                    break;
                case  'warning' :
                    $logger->warning();
                    break;
                case  'error' :
                    $logger->error();
                    break;
                case  'log' :
                    $logger->log();
                    break;
                case  'alert' :
                    $logger->alert();
                    break;
                case  'critical' :
                    $logger->critical();
                    break;
                case  'debug' :
                    $logger->debug();
                    break;
                case  'emergency' :
                    $logger->emergency();
                    break;
                case  'notice' :
                    $logger->notice();
                    break;
            }
        }
        catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }
    }
}
