<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\V2\FormSubmission;
use App\Mail\ProductRegistered as ProductRegisteredMail;

class ProductRegistered implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formSubmission;
    // protected $email;

    /**
    * Create a new job instance.
    *
    * @return void
    */
    public function __construct(FormSubmission $formSubmission)
    {
        $this->formSubmission = $formSubmission->fresh();
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {
        Mail::send(new ProductRegisteredMail($this->formSubmission));
    }
}
