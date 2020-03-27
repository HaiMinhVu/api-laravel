<?php

namespace App\Http\Requests\V2;

use Illuminate\Foundation\Http\FormRequest;

class FormSubmissionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "form_id" => "integer|required",
            "fields.*.id" => "integer|required",
            "fields.*.selected_option_id" => "integer|required_without:fields.*.value",
            "fields.*.value" => "required_without:fields.*.selected_option_id"
        ];
    }
}
