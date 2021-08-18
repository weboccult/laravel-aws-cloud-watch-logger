<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Drivers;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Weboccult\LaravelAwsCloudWatchLogger\Contracts\Driver;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Logger;

class CloudWatchDriver extends Driver
{

    protected array $settings;
    protected array $options;
    protected array $tags;
    protected Logger $logger;

    public function __construct(array $settings, array $options, array $tags = [])
    {
        $this->settings = $settings;
        $this->options = $options;
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
            return false;
        }
    }

    /**
     * @return false|void
     */
    public function dispatch(string $type, string $title)
    {
        if ($this->options['disabled'] == true) {
            return;
        }
        try {
            $payload = $this->preparePayload();
            $this->logger->$type($title, $payload);
        }
        catch (\Exception $e) {
            return false;
        }
    }
}
