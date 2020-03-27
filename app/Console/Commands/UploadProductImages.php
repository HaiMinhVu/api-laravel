<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\{FeaturedProduct, FileManager, Product, ProductImage};
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
    const WITH_RELATIONS = ['images', 'reticles'];

    private $manufacturer;
    private $client;
    private $s3Bucket;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "upload:product-images {--manufacturer= : Upload by manufacturer} {--sku=* : Upload by sku(s)} {--featured-id= : Upload by featured id} {--nsid=* : Upload by product nsid(s)}";

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
       $this->uploadByType();
    }

    private function uploadByType()
    {
        $skus = $this->option('sku');
        if(count($skus) > 0) {
            $this->uploadBySkus($skus);
        } else if($featuredId = $this->option('featured-id')) {
            $this->uploadByFeaturedId($featuredId);
        } else if($nsids = $this->option('nsid')) {
            $this->uploadByNSID($nsids);
        } else {
            $this->uploadByManufacturer();
        }
    }

    private function uploadByNSID($nsids)
    {
        $products = Product::withoutGlobalScopes()->with(self::WITH_RELATIONS)->where('nsid', $this->option('nsid'))->get();
        $this->uploadProductImages($products);
    }

    private function uploadBySkus($skus)
    {
        $products = Product::withoutGlobalScopes()->whereIn('sku', $skus)->with(self::WITH_RELATIONS)->get();
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
        $products = Product::active()->byManufacturer($this->manufacturer)->with(self::WITH_RELATIONS)->get();
        $this->uploadProductImages($products);
    }

    private function uploadProductImages($products)
    {
        $products->map(function($product){
            $this->sync($product->mainImage);

            $images = $product->images->merge($product->reticles);

            $images->map(function($image){
                if($image->fileManager()->exists()) {
                    $this->sync($image->fileManager);
                }
            });
        });
    }

    private function sync(FileManager $fileManagerModel)
    {
        try {
            $response = $fileManagerModel->syncWithS3();
            $this->info("{$response->filename} - {$response->status}");
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }
    }

}
