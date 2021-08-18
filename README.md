# This package is capable to log all your activities and SQL queries to AWS Cloud Watch.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/weboccult/laravel-aws-cloud-watch-logger.svg?style=flat-square)](https://packagist.org/packages/weboccult/laravel-aws-cloud-watch-logger)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/weboccult/laravel-aws-cloud-watch-logger/run-tests?label=tests)](https://github.com/weboccult/laravel-aws-cloud-watch-logger/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/weboccult/laravel-aws-cloud-watch-logger/Check%20&%20fix%20styling?label=code%20style)](https://github.com/weboccult/laravel-aws-cloud-watch-logger/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/weboccult/laravel-aws-cloud-watch-logger.svg?style=flat-square)](https://packagist.org/packages/weboccult/laravel-aws-cloud-watch-logger)

---

## Support me

[<img src="" width="419px" />](link)




## Installation

You can install the package via composer:

```bash
composer require weboccult/laravel-aws-cloud-watch-logger
```

[comment]: <> (You can publish and run the migrations with:)

[comment]: <> (```bash)

[comment]: <> (php artisan vendor:publish --provider="Weboccult\LaravelAwsCloudwatchLogger\LaravelAwsCloudwatchLoggerServiceProvider" --tag="laravel-aws-cloud-watch-logger-migrations")

[comment]: <> (php artisan migrate)

[comment]: <> (```)

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Weboccult\LaravelAwsCloudwatchLogger\LaravelAwsCloudwatchLoggerServiceProvider" --tag="laravel-aws-cloud-watch-logger-config"
```

This is the contents of the published config file:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | This value determines which of the following driver to use.
    | You can switch to a different driver at runtime.
    |
    */
    'default' => 'file',
    /*
    |--------------------------------------------------------------------------
    | List of Available Drivers
    |--------------------------------------------------------------------------
    |
    | These are the list of drivers to use for this package.
    | You can change the name. Then you'll have to change
    | it in the map array too.
    |
    */
    'drivers' => [
        'file'       => [
            // alert | critical | debug | emergency | error | info | log | notice | warning | write
            'log_level' => 'info',
        ],
        'cloudwatch' => [
            'credential'          => [
                /*
                 * Set your aws cloudwatch region and cloudwatch SKD version here...
                */
                'region'  => env('AWS_DEFAULT_REGION'),
                'version' => env('CLOUDWATCH_LOG_VERSION', 'latest'),
                /*
                 * Your aws credential
                */
                'key'     => env('AWS_ACCESS_KEY_ID'),
                'secret'  => env('AWS_SECRET_ACCESS_KEY'),
                'token'   => 'your AWS session token',
                // token is optional
            ],
            /*
             *  Set your log group name here...
             *  by default it will take Eactcard-Production group.
            */
            'log_group'           => env('CLOUDWATCH_LOG_GROUP', 'Eatcard-Production'),
            /*
             *  Set your log group stream here...
             *  by default it will take daily new date (format : 20XX-XX-XX).
            */
            'log_stream'          => env('CLOUDWATCH_LOG_STREAM', \Carbon\Carbon::now()->format('Y-m-d')),
            /*
             *  Set your retention period here...
             *  by default it will use null meaning no expiry date.
            */
            'retention'           => env('CLOUDWATCH_LOG_RETENTION', null),
            /*
             *  Set your retention batchSize here...
             *  by default it will use 10000.
             *  max batchSize is 10000, so you can not set value greater than 10000.
            */
            'batch_size'          => env('CLOUDWATCH_LOG_BATCH_SIZE', null),
            /*
             *  Set your log level here...
             *  @see \Monolog\Logger:class
            */
            'log_level'           => \Monolog\Logger::DEBUG,
            /*
             *  Set your log format here...
            */
            'formatter'           => \Monolog\Formatter\JsonFormatter::class,
            'logFormatterPattern' => '%channel%: %level_name%: %message% %context% %extra%',
        ]
    ],
    'options' => [
        /*
         *  Set your log group name here...
         *  by default it will take Eactcard-Production group.
        */
        'project_name' => env('PROJECT_NAME', env('APP_NAME', 'PROJECT_NAME_NOT_CONFIGURED')),
        /*
         * You can disable the logs here...
         *  Note : If you set true value then all the drivers won't able to send/write the logs
        */
        'disabled'     => env('DISABLE_CLOUDWATCH_LOG', false),
    ],
    /*
    |--------------------------------------------------------------------------
    | Class Maps
    |--------------------------------------------------------------------------
    |
    | This is the array of Classes that maps to Drivers above.
    | You can create your own driver if you like and add the
    | config in the drivers array and the class to use for
    | here with the same name. You will have to extend
    | Weboccult\LaravelAwsCloudWatchLogger\Drivers in your driver.
    |
    */
    'map'     => [
        'file'       => \Weboccult\LaravelAwsCloudWatchLogger\Drivers\FileDriver::class,
        'cloudwatch' => \Weboccult\LaravelAwsCloudWatchLogger\Drivers\CloudWatchDriver::class,
    ]
];
```

## Usage

```php
php :
$laravelAwsCloudWatchLogger = new Weboccult\LaravelAwsCloudwatchLogger();
echo $laravelAwsCloudWatchLogger->check();
echo $laravelAwsCloudWatchLogger->setStore($store)
                                ->setTitle('test')
                                ->setModule(\Weboccult\LaravelAwsCloudWatchLogger\Types\Modules::ORDER)
                                ->setOperation(\Weboccult\LaravelAwsCloudWatchLogger\Types\Operations::CREATE)
                                ->setData(["order_id" => 1])
                                ->info();

laravel facade :
use Weboccult\LaravelAwsCloudWatchLogger\Facades\LaravelAwsCloudWatchLogger;
LaravelAwsCloudWatchLogger::check()
LaravelAwsCloudWatchLogger::setStore($store)
                          ->setTitle('test')
                          ->setTags(['test', 'any'])
                          ->setModule(\Weboccult\LaravelAwsCloudWatchLogger\Types\Modules::ORDER)
                          ->setOperation(\Weboccult\LaravelAwsCloudWatchLogger\Types\Operations::CREATE)
                          ->setData(["order_id" => 1])
                          ->info();

laravel helper :
laravelAwsCloudwatchLogger()->check()
laravelAwsCloudwatchLogger()->setStore($store)
                            ->setTags(['test', 'any'])
                            ->setTitle('test')
                            ->setModule(\Weboccult\LaravelAwsCloudWatchLogger\Types\Modules::ORDER)
                            ->setOperation(\Weboccult\LaravelAwsCloudWatchLogger\Types\Operations::CREATE)
                            ->setData(["order_id" => 1])
                            ->info();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Darshit Hedpara](https://github.com/darshithedpara)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
