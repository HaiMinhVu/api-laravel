<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\V2\{
    File as FileObserver,
    FormSubmission as FormSubmissionObserver
};
use App\Models\V2\{
    File as FileModel,
    FormSubmission as FormSubmissionModel
};
use App\Observers\{
    Product as ProductObserverV1
};
use App\Models\{
    Product as ProductModelV1
};

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

    /**
    * Bootstrap any application services.
    *
    * @return void
    */
    public function boot()
    {
        $this->registerObservers();
        // $this->registerObserversV1();
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

    private function registerObservers()
    {
        FormSubmissionModel::observe(FormSubmissionObserver::class);
        FileModel::observe(FileObserver::class);
    }

    private function registerObserversV1()
    {
        ProductModelV1::observe(ProductObserverV1::class);
    }
}
