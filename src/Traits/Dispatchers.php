<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Traits;

/**
 * dispatch helpers
 */
trait Dispatchers
{
    public function info()
    {
        $this->dispatch('info');
    }

    public function warning()
    {
        $this->dispatch('warning');
    }

    public function error()
    {
        $this->dispatch('error');
    }

    public function log()
    {
        $this->dispatch('log');
    }

    public function alert()
    {
        $this->dispatch('alert');
    }

    public function critical()
    {
        $this->dispatch('critical');
    }

    public function debug()
    {
        $this->dispatch('debug');
    }

    public function emergency()
    {
        $this->dispatch('emergency');
    }

    public function notice()
    {
        $this->dispatch('notice');
    }
}
