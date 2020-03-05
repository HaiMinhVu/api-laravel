<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'zip' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
            'address1' => 'required',
            'address2' => 'string|nullable',
            'city' => 'required|string',
            'state' => 'required|string',
            'proof_of_purchase' => 'nullable',
            'DealerStore' => 'required|string',
            'price_paid' => 'required',
            'product_id' => 'required|integer',
            'date_purchased' => 'required',
            'satisfaction' => 'required|integer',
            'serial_number' => 'nullable',
            'comments' => 'nullable'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
