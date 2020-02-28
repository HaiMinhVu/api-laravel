<?php

namespace App\Mail;

use App\Models\ProductRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductRegistered extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The model instance.
     *
     * @var ProductRegistration
     */
    public $productRegistration;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProductRegistration $productRegistration)
    {
        $this->productRegistration = $productRegistration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.product-registration')->with(['productRegistration', $this->productRegistration]);
    }
}
