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

    public function setModel(Model $model)
    {
        $this->model = $model;
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
