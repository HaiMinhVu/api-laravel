<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\{FeaturedProduct, Product, ProductImage};
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
    protected $signature = "upload:product-images {--manufacturer= : Upload by manufacturer} {--sku=* : Upload by sku(s)} {--featured-id= : Upload by featured id}";

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
        } else if($featuredId = $this->option('featured-id')) {
            $this->uploadByFeaturedId($featuredId);
        } else {
            $this->uploadByManufacturer();
        }
    }

    private function uploadBySkus($skus)
    {
        $products = Product::whereIn('sku', $skus)->with('images')->get();
        $this->uploadProductImages($products);
    }

    private function uploadByFeaturedId($featuredId)
    {
        $featuredProductParent = FeaturedProduct::with(['featuredProducts'])->find($featuredId);
        $featuredProducts = $featuredProductParent->featuredProducts->map(function($featuredProduct){
            return $featuredProduct->product;
        });
        $this->uploadProductImages($featuredProducts);
    }

    private function uploadByManufacturer()
    {
        $this->setManufacturer();
        $products = Product::active()->byManufacturer($this->manufacturer)->with('images')->get();
        $this->uploadProductImages($products);
    }

    private function uploadProductImages($products)
    {
        $products->map(function($product){
            $response = $product->mainImage->syncWithS3();
            $this->parseInfo($response);

            $product->images->map(function($image){
                if($image->fileManager()->exists()) {
                    $response = $image->fileManager->syncWithS3();
                    $this->parseInfo($response);
                }
            });
        });
    }

    private function parseInfo($response)
    {
        $this->info(PHP_EOL);
        $this->info("File Name: {$response->filename}".PHP_EOL);
        $this->info("Success: {$response->success}".PHP_EOL);
        $this->info("Status: {$response->status}".PHP_EOL);
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
