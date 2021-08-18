<?php

namespace Weboccult\LaravelAwsCloudWatchLogger;

use Weboccult\LaravelAwsCloudWatchLogger\Contracts\Driver;
use Weboccult\LaravelAwsCloudWatchLogger\Traits\Checkable;
use Weboccult\LaravelAwsCloudWatchLogger\Traits\Dispatchers;
use Weboccult\LaravelAwsCloudWatchLogger\Types\Modules;
use Weboccult\LaravelAwsCloudWatchLogger\Types\Operations;
use Illuminate\Database\Eloquent\Model;
use Monolog\Logger;
use ReflectionClass;

class LaravelAwsCloudWatchLogger
{
    use Checkable, Dispatchers;

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

    protected function validateDriver()
    {
        $conditions = [
            'Driver not selected or default driver does not exist.'                             => empty($this->driver),
            'Driver not found or not properly mapped in config file. Try updating the package.' => !isset ($this->config['drivers'][$this->driver]) || empty($this->config['drivers'][$this->driver]) || !isset($this->config['map'][$this->driver]) || empty($this->config['map'][$this->driver]),
            'Driver source not found. Please update the package.'                               => isset($this->config['map'][$this->driver]) && !class_exists($this->config['map'][$this->driver]),
            'Driver must be an instance of Contracts\Driver.'                                   => isset ($this->config['map'][$this->driver]) && !(new ReflectionClass($this->config['map'][$this->driver]))->isSubclassOf(Driver::class),
        ];
        foreach ($conditions as $ex => $condition) {
            throw_if($condition, new \Exception($ex));
        }
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
     * @param $data
     * @return $this
     */
    public function setData($data): LaravelAwsCloudWatchLogger
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

    public function validatePayload(array $data): bool
    {
        if (empty($data)) {
            return false;
        }
        $conditions = [
            'store is required.!' => empty($this->store),
        ];
        foreach ($conditions as $ex => $condition) {
            throw_if($condition, new \Exception($ex));
        }
        $this->data = $data;
        return true;
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
