<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    FileManager,
    Product
};

class UploadProductDocuments extends Command
{
    const DEFAULT_MANUFACTURER = 'pulsar';
    const WITH_RELATIONS = ['specSheets', 'manuals'];

    private $manufacturer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:product-documents {--manufacturer= : Upload by manufacturer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload product documents to S3 bucket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->uploadByManufacturer();
    }

    private function setManufacturer()
    {
        $manufacturer = $this->option('manufacturer');
        $this->manufacturer = ($manufacturer === null) ? self::DEFAULT_MANUFACTURER : $manufacturer;
    }

    private function uploadByManufacturer()
    {
        $this->setManufacturer();
        $products = Product::active()->byManufacturer($this->manufacturer)->with(self::WITH_RELATIONS)->get();
        $this->uploadProductDocuments($products);
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

    private function uploadProductDocuments($products)
    {
        $products->map(function($product){
            $files = collect($product->manuals)->merge(collect($product->specSheets));

            $files->map(function($file){
                $this->sync($file);
            });
        });
    }
}
