<?php

namespace App\Observers\V2;

use App\Jobs\ProductRegistered;
use App\Models\V2\{
    Form,
    FormSubmission as FormSubmissionModel
};

class FormSubmission
{
    /**
    * Handle the FormSubmission "updated" event.
    *
    * @param  \App\Models\V2\FormSubmission  $formSubmission
    * @return void
    */
    public function updated(FormSubmissionModel $formSubmission)
    {
        $this->handleProductRegistration($formSubmission);
    }

    private function handleProductRegistration(FormSubmissionModel $formSubmission)
    {
        if($formSubmission->form->name == 'product-registration') {
            ProductRegistered::dispatch($formSubmission)->onQueue('mail');
        }
    }
}
