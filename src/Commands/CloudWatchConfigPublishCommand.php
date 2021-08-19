<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Commands;

use Illuminate\Console\Command;

class CloudWatchConfigPublishCommand extends Command
{
    public $signature = 'cloudwatch:publish';

    public $description = 'Publish cloudwatch config file';

    public function handle()
    {
        if (file_exists(config_path('cloudwatch.php'))) {
            $this->error('cloudwatch.php is already exist. config file publish failed.!');
            $answer = $this->ask('Are you sure you want to replace the cloudwatch config file ? [y/N]', 'N');
            if ($answer == 'y' || $answer == 'Y') {
                copy(__DIR__ . '/../../config/cloudwatch.php', config_path('cloudwatch.php'));
                $this->info('Config file published successfully.!');
            }
            return;
        }
        $this->call('vendor:publish', ['--tag' => "cloudwatch-config"]);
    }
}
