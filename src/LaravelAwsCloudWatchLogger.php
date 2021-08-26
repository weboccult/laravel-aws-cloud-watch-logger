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

    protected $model = null;

    protected string $module;

    protected string $operation;

    public function __construct()
    {
        $this->config = config('cloudwatch');
        if (!file_exists(config_path('cloudwatch.php'))) {
            throw new \Exception('cloudwatch.php not found in config folder you need publish it first.!');
        }
        $this->via($this->config['default']);
    }

    /**
     * @param string $driver
     * @return $this
     */
    public function via(string $driver): LaravelAwsCloudWatchLogger
    {
        $this->driver = $driver;
        if (isset($this->config['default']) && !empty($this->config['default']) && isset($this->config['drivers'][$driver])) {
            $this->settings = $this->config['drivers'][$driver];
        } else {
            throw new \InvalidArgumentException('Default driver is invalid.!');
        }
        return $this;
    }

    /**
     * @param int|string $level
     * @return $this
     */
    public function setLogLevel($level): LaravelAwsCloudWatchLogger
    {
        $reflect = new ReflectionClass(Logger::class);
        if ($this->config['default'] == 'cloudwatch') {
            if (!isset(array_flip($reflect->getConstants())[$level])) {
                throw new \Exception('LogLevel must be an constant of \Monolog\Logger::class.');
            }
            $constName = array_flip($reflect->getConstants())[$level];
            $this->settings['log_level'] = $reflect->getConstant($constName);
        }
        if ($this->config['default'] == 'file') {
            $supportedLogLevels = ['alert','critical','debug','emergency','error','info','log','notice','warning','write'];
            if (!in_array($level, $supportedLogLevels)) {
                throw new \Exception('LogLevel must be an one of [].');
            }
            $this->settings['log_level'] = $level;
        }
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
    public function setModel($model): LaravelAwsCloudWatchLogger
    {
        if (!$model instanceof Model) {
            throw new \Exception('Model must me an instance of Illuminate\Database\Eloquent\Model');
        }
        $this->model = $model;
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
        return new $class($this->settings, $this->config['options'], $this->config['model'], $this->tags);
    }

    /**
     * @return mixed
     */
    public function dispatch(string $type)
    {
        $conditions = [
            'Module can not be empty.'                  => empty($this->module),
            'Operation can not be empty.'               => empty($this->operation),
            'Data can not be empty.'                    => empty($this->data),
        ];
        if (
            isset($this->config['model']['class']) &&
            !empty($this->config['model']['class'])
        ) {
            $conditions = array_merge( $conditions, [
                'Model is required as you have defined in cloudwatch config file.' => empty($this->model),
            ],);
            if ($this->config['model']['class'] instanceof Model) {
                $conditions = array_merge( $conditions, [
                    'Model must be an instance of Illuminate\Database\Eloquent\Model class.' => !($this->model instanceof Model),
                    'Model is required as you have defined in cloudwatch config file.' => empty($this->model),
                ]);
            }
        }
        foreach ($conditions as $ex => $condition) {
            throw_if($condition, new \Exception($ex));
        }
        $driver = $this->getDriverInstance();
        $driver->setTitle($this->title);
        $driver->setData($this->data);
        $driver->setModel($this->config['model']['class']);
        $driver->setModule($this->module);
        $driver->setOperation($this->operation);
        return $driver->dispatch($type, $this->title);
    }
}
