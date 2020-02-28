<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAWSS3Facade();
    }

    private function registerAWSS3Facade()
    {
        $this->app->bind('aws_s3', function() {
            return new \App\Services\AWS\S3;
        });

        if (!class_exists('S3')) {
            class_alias('\App\Facades\S3', 'S3');
        }
    }
}
