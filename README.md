<h6 align="center">
    <img src="https://eatcard.s3.eu-central-1.amazonaws.com/WOT-Logo-2-(1280x640).png" width="250"/>
</h6>
<h2 align="center">
    Laravel Aws CloudWatch Logger
</h2>
<br>

---

[![Latest Version on Packagist](https://img.shields.io/packagist/v/weboccult/laravel-aws-cloud-watch-logger.svg?style=flat-square)](https://packagist.org/packages/weboccult/laravel-aws-cloud-watch-logger)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/weboccult/laravel-aws-cloud-watch-logger/run-tests?label=tests)](https://github.com/weboccult/laravel-aws-cloud-watch-logger/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/weboccult/laravel-aws-cloud-watch-logger.svg?style=flat-square)](https://packagist.org/packages/weboccult/laravel-aws-cloud-watch-logger)

[comment]: <> ([![GitHub Code Style Action Status]&#40;https://img.shields.io/github/workflow/status/weboccult/laravel-aws-cloud-watch-logger/Check%20&%20fix%20styling?label=code%20style&#41;]&#40;https://github.com/weboccult/laravel-aws-cloud-watch-logger/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain&#41;)

---


This package is capable to log all your activities and SQL queries to AWS Cloud Watch.

## Installation

#### You can install the package via composer:

```bash
composer require weboccult/laravel-aws-cloud-watch-logger
```

#### You can publish the config file with:

```bash
php artisan cloudwatch:publish
```

#### This is the contents of the published config file:

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
    | Global options
    |--------------------------------------------------------------------------
    |
    | These are the global option for this package and it will determine how the package will operate.
    |
    */
    'options' => [
        /*
         * Set your log group name here...
         * by default it will take Eactcard-Production group.
        */
        'project_name' => env('PROJECT_NAME', env('APP_NAME', 'PROJECT_NAME_NOT_CONFIGURED')),
        /*
         * You can disable the logs here...
         * Note : If you set true value then all the drivers won't able to send/write the logs
        */
        'disabled'     => env('DISABLE_CLOUDWATCH_LOG', false),
    ],
    /*
    |--------------------------------------------------------------------------
    | Centric Model
    |--------------------------------------------------------------------------
    |
    | This is centric model for this package and it will add the extra fields from a defined model
    |
    */
    'model'   => [
        /*
         * Set your centric model here which will be used for root level fields
         * by default it will take User Model you can change as per your project's database...
         * Note : class is completely optional
        */
        'class'  => null, // App\Models\User::class,
        /*
         * Here you can define field which will be used by this package...
         * Note : Fields are completely optional, If you do a defined field in the array,
         *         then all the logs will be an extra fields in the root level of your JSON.
        */
        'fields' => [
            // 'userId' => 'id',
        ],
    ],
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
             * Set your log group name here...
             * by default it will take Eactcard-Production group.
            */
            'log_group'           => env('CLOUDWATCH_LOG_GROUP', 'Eatcard-Production'),
            /*
             * Set your log group stream here...
             * By default it will take daily new date (format : 20XX-XX-XX).
            */
            'log_stream'          => env('CLOUDWATCH_LOG_STREAM', \Carbon\Carbon::now()->format('Y-m-d')),
            /*
             * Set your retention period here...
             * By default it will use null meaning no expiry date.
            */
            'retention'           => env('CLOUDWATCH_LOG_RETENTION', null),
            /*
             * Set your retention batchSize here...
             * By default it will use 10000.
             * Max batchSize is 10000, so you can not set value greater than 10000.
            */
            'batch_size'          => env('CLOUDWATCH_LOG_BATCH_SIZE', null),
            /*
             * Set your log level here...
             * @see \Monolog\Logger:class
            */
            'log_level'           => \Monolog\Logger::DEBUG,
            /*
             * Set your log format here...
            */
            'formatter'           => \Monolog\Formatter\JsonFormatter::class,
            'logFormatterPattern' => '%channel%: %level_name%: %message% %context% %extra%',
        ]
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

#### Core PHP:

```php
$laravelAwsCloudWatchLogger = new Weboccult\LaravelAwsCloudwatchLogger();
echo $laravelAwsCloudWatchLogger->check();
echo $laravelAwsCloudWatchLogger->setStore($store)
                                ->setTitle('test')
                                ->setModule(\Weboccult\LaravelAwsCloudWatchLogger\Types\Modules::ORDER)
                                ->setOperation(\Weboccult\LaravelAwsCloudWatchLogger\Types\Operations::CREATE)
                                ->setData(["order_id" => 1])
                                ->info();
```

#### Laravel Facade:

```php
use Weboccult\LaravelAwsCloudWatchLogger\Facades\LaravelAwsCloudWatchLogger;

LaravelAwsCloudWatchLogger::check()
LaravelAwsCloudWatchLogger::setStore($store)
                          ->setTitle('test')
                          ->setTags(['test', 'any'])
                          ->setModule(\Weboccult\LaravelAwsCloudWatchLogger\Types\Modules::ORDER)
                          ->setOperation(\Weboccult\LaravelAwsCloudWatchLogger\Types\Operations::CREATE)
                          ->setData(["order_id" => 1])
                          ->info();
```

#### Laravel Helper:

```php
laravelAwsCloudwatchLogger()->check()
laravelAwsCloudwatchLogger()->setStore($store)
                            ->setTags(['test', 'any'])
                            ->setTitle('test')
                            ->setModule(\Weboccult\LaravelAwsCloudWatchLogger\Types\Modules::ORDER)
                            ->setOperation(\Weboccult\LaravelAwsCloudWatchLogger\Types\Operations::CREATE)
                            ->setData(["order_id" => 1])
                            ->info();
```

#### You can change the log driver at runtime:

```php
laravelAwsCloudwatchLogger()->via('file') // <=== using via() method (available drivers [ file, cloudwatch] )
                            ->...
                            ->...
                            ->info();
```

#### Silent Logger:

It will not throw any error, just put file log if anything goes wrong.

```php
laravelAwsCloudWatchLoggerSilent(function () {
            laravelAwsCloudwatchLogger()
                    ->setTitle('test')
                    ->setModule(\Weboccult\LaravelAwsCloudWatchLogger\Types\Modules::ORDER)
                    ->setLogLevel('error')
                    ->setTags(['error'])
                    ->setOperation(\Weboccult\LaravelAwsCloudWatchLogger\Types\Operations::CREATE)
                    ->setData(["order_id" => 1])
                    ->info();
    });
```

#### Inline Logger:

It will same as Silent Logger, but with compact syntax.

```php
use \Weboccult\LaravelAwsCloudWatchLogger\Types\Modules;
use \Weboccult\LaravelAwsCloudWatchLogger\Types\Operations

laravelAwsCloudWatchLoggerInline('info', 'test', ["order_id" => 1], Modules::ORDER, Operations::CREATE, null, ['tag1', 'tag2'] );
```

## Testing

```bash
./vendor/bin/pest
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
