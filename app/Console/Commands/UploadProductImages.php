<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\{Product, ProductImage};
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\ObjectUploader;
use Storage;

/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class UploadProductImages extends Command
{
    const DEFAULT_MANUFACTURER = 'pulsar';
    const TMP_DIRECTORY = 'tmp';

    private $manufacturer;
    private $client;
    private $s3Bucket;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "upload:product-images {--manufacturer= : Upload by manufacturer} {--sku=* : The upload by sku(s)}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Upload product images to S3 bucket";

    public function __construct()
    {
        parent::__construct();
        $this->client = new S3Client([
            'profile' => 'default',
            'region' => config('services.aws.region'),
            'version' => config('services.aws.version')
        ]);
        $this->s3Bucket = config('services.aws.bucket');
    }

    private function setManufacturer()
    {
        $manufacturer = $this->option('manufacturer');
        $this->manufacturer = ($manufacturer === null) ? self::DEFAULT_MANUFACTURER : $manufacturer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $skus = $this->option('sku');
        if(count($skus) > 0) {
            $this->uploadBySkus($skus);
        } else {
            $this->uploadByManufacturer();
        }
    }

    private function uploadBySkus($skus)
    {
        $products = Product::whereIn('sku', $skus)->with('images')->get();
        $this->uploadProductImages($products);
    }

    private function uploadByManufacturer()
    {
        $this->setManufacturer();
        $products = Product::active()->byManufacturer($this->manufacturer)->with('images')->get();
        dd($products);
        $this->uploadProductImages($product);
    }

    private function uploadProductImages($products)
    {
        $products->map(function($product){
            $filePath = $product->mainImage->filePath();
            $fileName = $this->s3FileName($filePath);
            $fileUrl = $product->mainImage->url();
            $this->uploadProductImage($fileName, $fileUrl);

            $product->images->map(function($image){
                if($image->fileManager()->exists()) {
                    $filePath = $image->fileManager->filePath();
                    $fileName = $this->s3FileName($filePath);
                    $fileUrl = $image->fileManager->url();
                    $this->uploadProductImage($fileName, $fileUrl);
                }
            });
        });
    }

    private function uploadProductImage($fileName, $fileUrl)
    {
        if(!$this->doesFileExistInS3($fileName)) {
          try {
              $contents = file_get_contents($fileUrl);
              $this->uploadFile($fileName, $contents);
              $this->info("Uploaded file: {$fileName}");
          } catch(\Exception $e) {
              $this->error("Failed to upload file: {$fileName}");
          }
        }
    }

    private function doesFileExistInS3($key) {
        return $this->client->doesObjectExist(config('services.aws.bucket'), $key);
    }

    private function s3FileName($filePath)
    {
        return "{$this->manufacturer}/{$filePath}";
    }

    private function uploadFile($key, $source)
    {
        $uploader = new ObjectUploader(
            $this->client,
            $this->s3Bucket,
            $key,
            $source
        );
        return $uploader->upload();
    }

}
