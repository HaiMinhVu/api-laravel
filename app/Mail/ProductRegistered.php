<?php

namespace App\Mail;

use App\Models\V2\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductRegistered extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The FormSubmission associated with the product submission.
     *
     * @var FormSubmission
     */
    public $formSubmission;
    public $logoUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(FormSubmission $formSubmission)
    {
        $this->formSubmission = $formSubmission;
        $this->logoUrl = $this->formSubmission->brand->logoUrl();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to([$this->formSubmission->getValueByLabel('email'), 'hvu@sellmark.net'])
                    ->from(config('mail.from.address'), "No Reply at {$this->formSubmission->brand->name}")
                    ->subject("{$this->formSubmission->brand->name} - Product Registration")
                    ->view('emails.product-registration')
                    ->with([
                        'formSubmissions' => $this->formSubmission->getSimpleSubmissionData(),
                        'brandLogoUrl' => $this->logoUrl
                    ]);
    }
}
