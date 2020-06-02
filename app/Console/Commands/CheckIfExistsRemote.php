<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FileManager;
use S3;

class CheckIfExistsRemote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:check-remote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if product exists on remote, change flag with results';

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
        $fileManagerModels = FileManager::doesNotExistOnS3()->get();

        $bar = $this->output->createProgressBar($fileManagerModels->count());

        $fileManagerModels->map(function($fileManagerModel) use ($bar) {
            $filePath = $fileManagerModel->s3FilePath();
            // dd($fileManagerModel);
            if(S3::doesFileExistInS3($filePath)) {
                $fileManagerModel->exists_on_s3 = 1;
                // dd($fileManagerModel);
                $fileManagerModel->save();
                // dd($fileManagerModel);
                // $this->info("{$filePath} - updated");
            } else {
                // $this->info("{$filePath} - does not exist on S3");
            }
            $bar->advance();
        });

        $bar->finish();
    }
}
