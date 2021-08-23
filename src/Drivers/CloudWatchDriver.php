<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Drivers;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Illuminate\Support\Facades\Log;
use Weboccult\LaravelAwsCloudWatchLogger\Contracts\Driver;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Logger;

class CloudWatchDriver extends Driver
{

    protected array $settings;
    protected array $options;
    protected array $modelConfig;
    protected array $tags;
    protected $logger;

    public function __construct(array $settings, array $options, array $modelConfig, array $tags = [])
    {
        $this->settings = $settings;
        $this->options = $options;
        $this->modelConfig = $modelConfig;
        $this->tags = $tags;
        $this->logger = $this->getLogger();
    }

    /**
     * @return false|Logger
     */
    protected function getLogger()
    {
        try {
            $client = new CloudWatchLogsClient($this->settings['credential']);
            $handler = new CloudWatch($client, $this->settings['log_group'], $this->settings['log_stream'], $this->settings['retention'], $this->settings['batch_size'], $this->tags, $this->settings['log_level']);
            $logger = new Logger($this->options['project_name']);
            $logger->pushHandler($handler);
            return $logger;
        }
        catch (\Exception $e) {
            Log::info('ERROR while connectng to cloudwatch'. PHP_EOL . '-------------------------------' .PHP_EOL . $e->getMessage() . PHP_EOL . '-------------------------------' . PHP_EOL);
            return false;
        }
    }

    /**
     * @return false|void
     */
    public function dispatch(string $type, string $title): void
    {
        if ($this->options['disabled'] == true || $this->logger == false) {
            return;
        }
        try {
            $payload = $this->preparePayload();
            $this->logger->$type($title, $payload);
        }
        catch (\Exception $e) {
            return;
        }
    }
}
