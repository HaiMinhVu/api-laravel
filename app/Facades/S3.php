<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class S3 extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'aws_s3';
    }
}
