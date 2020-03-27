<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FeaturedProduct;

class UploadFeaturedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:featured-images {--parent-id= : Upload by parent id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload featured images to S3 bucket';

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
        if($featuredProducts = optional(FeaturedProduct::where('pid', $this->option('parent-id')))->get()) {
            $featuredProducts->map(function($featuredProduct){
                $fileManager = optional($featuredProduct->product->mainImage);
                // dd($featuredProduct->product->fileManager);
                $this->info("Syncing file: {$fileManager->file_name}");
                try {
                    $fileManager->syncWithS3();
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            });
        }
    }
}
