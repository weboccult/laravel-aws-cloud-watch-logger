<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Traits;

use Weboccult\LaravelAwsCloudWatchLogger\Contracts\Driver;
use ReflectionClass;
/**
 * validation helpers
 */
trait Validators
{

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

    protected function validatePayload(array $data): bool
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
}
