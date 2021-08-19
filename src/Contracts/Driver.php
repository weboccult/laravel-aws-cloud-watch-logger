<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class Driver
{
    use Setters, PayloadPrepare;

    protected array $options;

    protected array $settings;

    protected string $driver;

    protected array $tags;

    protected string $title;

    protected array $data;

    protected Model $store;

    protected string $operation;

    protected string $module;

    /**
     * Driver constructor.
     * @param array $settings
     */
    abstract public function __construct(array $settings, array $options, array $tags);

    abstract public function dispatch(string $type, string $title): void;
}
