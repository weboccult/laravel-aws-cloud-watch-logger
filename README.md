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
    'Documented soon'
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
                          ->setModule(\Weboccult\LaravelAwsCloudWatchLogger\Types\Modules::ORDER)
                          ->setOperation(\Weboccult\LaravelAwsCloudWatchLogger\Types\Operations::CREATE)
                          ->setData(["order_id" => 1])
                          ->info();

laravel helper :
laravelAwsCloudwatchLogger()->check()
laravelAwsCloudwatchLogger()->setStore($store)
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

- [Darshit Hedpara](https://github.com/weboccult)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
