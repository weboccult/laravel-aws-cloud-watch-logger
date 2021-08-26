<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Contracts;

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

    /**
     * @param $model
     */
    public function setModel($model)
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
