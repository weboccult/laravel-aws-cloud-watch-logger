<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * helper method for Driver class
 */
trait Setters
{
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function setStore(Model $store)
    {
        $this->store = $store;
    }

    public function setModule(string $module)
    {
        $this->module = $module;
    }

    public function setOperation(string $operation)
    {
        $this->operation = $operation;
    }
}
