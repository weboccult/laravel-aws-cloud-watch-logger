<?php

namespace Weboccult\LaravelAwsCloudWatchLogger;

use Weboccult\LaravelAwsCloudWatchLogger\Traits\Checkable;
use Weboccult\LaravelAwsCloudWatchLogger\Traits\Dispatchers;
use Weboccult\LaravelAwsCloudWatchLogger\Traits\Validators;
use Weboccult\LaravelAwsCloudWatchLogger\Types\Modules;
use Weboccult\LaravelAwsCloudWatchLogger\Types\Operations;
use Illuminate\Database\Eloquent\Model;
use Monolog\Logger;
use ReflectionClass;

class LaravelAwsCloudWatchLogger
{
    use Checkable, Validators, Dispatchers;

    protected array $config;

    protected array $settings;

    protected string $driver;

    protected array $tags = [];

    protected string $title = '';

    protected array $data = [];

    protected Model $store;

    protected string $module;

    protected string $operation;

    public function __construct()
    {
        $this->config = config('cloudwatch');
        $this->via($this->config['default']);
    }

    /**
     * @param string $driver
     * @return $this
     */
    public function via(string $driver): LaravelAwsCloudWatchLogger
    {
        $this->driver = $driver;
        $this->settings = $this->config['drivers'][$driver];
        return $this;
    }

    /**
     * @param int $level
     * @return $this
     */
    public function setLogLevel(int $level): LaravelAwsCloudWatchLogger
    {
        $reflect = new ReflectionClass(Logger::class);
        if (!isset(array_flip($reflect->getConstants())[$level])) {
            throw new \Exception('LogLevel must be an constant of \Monolog\Logger::class.');
        }
        $constName = array_flip($reflect->getConstants())[$level];
        $this->settings['log_level'] = $reflect->getConstant($constName);
        return $this;
    }

    /**
     * @param string $group
     * @return $this
     */
    public function setGroup(string $group): LaravelAwsCloudWatchLogger
    {
        $this->settings['log_group'] = $group;
        return $this;
    }

    /**
     * @param string $stream
     * @return $this
     */
    public function setStream(string $stream): LaravelAwsCloudWatchLogger
    {
        $this->settings['log_stream'] = $stream;
        return $this;
    }

    /**
     * @param int $days
     * @return $this
     */
    public function setRetentionPeriod(int $days): LaravelAwsCloudWatchLogger
    {
        $this->settings['retention'] = $days;
        return $this;
    }

    /**
     * @param array $tags
     * @return $this
     */
    public function setTags(array $tags): LaravelAwsCloudWatchLogger
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param int $batchSize
     * @return $this
     */
    public function setBatchSize(int $batchSize): LaravelAwsCloudWatchLogger
    {
        if ($batchSize > 10000) {
            throw new \InvalidArgumentException('Batch size can not be greater than 10000');
        }
        $this->settings['batch_size'] = $batchSize;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data): LaravelAwsCloudWatchLogger
    {
        $this->validatePayload($data);
        return $this;
    }

    /**
     * @param string $title
     * @throws \Exception
     */
    public function setTitle(string $title): LaravelAwsCloudWatchLogger
    {
        if (empty($title)) {
            throw new \Exception('title can not be empty');
        }
        $this->title = $title;
        return $this;
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function setStore($store): LaravelAwsCloudWatchLogger
    {
        if (!$store instanceof Model) {
            throw new \Exception('title can not be empty');
        }
        $this->store = $store;
        return $this;
    }

    public function setModule(string $module): LaravelAwsCloudWatchLogger
    {
        $reflect = new ReflectionClass(Modules::class);
        if (!isset(array_flip($reflect->getConstants())[$module])) {
            throw new \Exception('Module not supported please check \Weboccult\LaravelAwsCloudWatchLogger\Types\Operations::class.');
        }
        $constName = array_flip($reflect->getConstants())[$module];
        $this->module = $reflect->getConstant($constName);
        return $this;
    }

    public function setOperation(string $operation): LaravelAwsCloudWatchLogger
    {
        $reflect = new ReflectionClass(Operations::class);
        if (!isset(array_flip($reflect->getConstants())[$operation])) {
            throw new \Exception('Operation not supported please check \Weboccult\LaravelAwsCloudWatchLogger\Types\Operations::class.');
        }
        $constName = array_flip($reflect->getConstants())[$operation];
        $this->operation = $reflect->getConstant($constName);
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getDriverInstance()
    {
        $this->validateDriver();
        $class = $this->config['map'][$this->driver];
        return new $class($this->settings, $this->config['options'], $this->tags);
    }

    /**
     * @return mixed
     */
    public function dispatch(string $type)
    {
        $conditions = [
            'Module can not be empty.'                  => empty($this->module),
            'Operation can not be empty.'               => empty($this->operation),
            'Store can not be empty.'                   => empty($this->store),
            'Data can not be empty.'                    => empty($this->data),
            'Store must be an instance of Model class.' => !($this->store instanceof Model),
        ];
        foreach ($conditions as $ex => $condition) {
            throw_if($condition, new \Exception($ex));
        }
        $driver = $this->getDriverInstance();
        $driver->setTitle($this->title);
        $driver->setData($this->data);
        $driver->setStore($this->store);
        $driver->setModule($this->module);
        $driver->setOperation($this->operation);
        return $driver->dispatch($type, $this->title);
    }
}
