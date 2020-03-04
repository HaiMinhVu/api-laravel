<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\SliderImage;

/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class UploadSliderImages extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "upload:slider-images {--slider-id= : Id of slider}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Upload slider images to S3 bucket";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($sliderImages = optional(SliderImage::find($this->option('slider-id')))->images) {
            $sliderImages->map(function($sliderImage){
                $this->info("Syncing file: {$sliderImage->fileManager->file_name}");
		try {
                    $sliderImage->fileManager->syncWithS3();
		} catch (\Exception $e) {
		    $this->error($e->getMessage());
  		}
            });
        }
    }
}
