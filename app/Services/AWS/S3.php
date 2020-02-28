<?php

namespace App\Services\AWS;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\ObjectUploader;

class S3
{
    private $client;
    private $bucket;

    public function __construct()
    {
        $this->client = new S3Client([
            'profile' => 'default',
            'region' => config('services.aws.region'),
            'version' => config('services.aws.version')
        ]);
        $this->bucket = config('services.aws.bucket');
    }


    public function syncFromUrl($fileName, $fileUrl)
    {
        $success = true;
        if(!$this->doesFileExistInS3($fileName)) {
          $fileContents = file_get_contents($fileUrl);
          try {
              $this->upload($fileName, $fileContents);
          } catch(\Exception $e) {
              // dd($e->getMessage());
              $success = false;
          }
        }
        return $success;
    }

    public function doesFileExistInS3($key) {
        return $this->client->doesObjectExist($this->bucket, $key);
    }

    private function upload($key, $source)
    {
        $uploader = new ObjectUploader(
            $this->client,
            $this->bucket,
            $key,
            $source
        );
        return $uploader->upload();
    }

}
