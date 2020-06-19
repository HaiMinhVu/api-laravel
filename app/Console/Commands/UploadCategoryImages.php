<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductCategory;

class UploadCategoryImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:category-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload category images to S3 bucket';

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
        $categories = ProductCategory::whereHas('products')->get();

        $categories->map(function($category){
            try {
                $response = optional($category->fileManager)->syncWithS3();
                if($response) $this->info("{$response->filename} - {$response->status}");
            } catch (\Exception $e) {
                $this->error("{$category->fileManager->file_name} - error uploading");
            }
        });
    }
}
