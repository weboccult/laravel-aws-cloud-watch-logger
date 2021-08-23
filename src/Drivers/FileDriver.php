<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Drivers;

use Weboccult\LaravelAwsCloudWatchLogger\Contracts\Driver;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class FileDriver extends Driver
{
    protected array $settings;
    protected array $options;
    protected array $modelConfig;
    protected array $tags;

    public function __construct(array $settings, array $options, array $modelConfig, array $tags = [])
    {
        $this->settings = $settings;
        $this->options = $options;
        $this->modelConfig = $modelConfig;
        $this->tags = $tags;
    }

    public function dispatch(string $type, string $title): void
    {
        $payload = $this->preparePayload();
        Log::info($title . ' => ' . implode('|', $this->tags) . PHP_EOL . '-------------------------------' . PHP_EOL . json_encode($payload, JSON_PRETTY_PRINT) . PHP_EOL . '-------------------------------' . PHP_EOL);
    }
}
