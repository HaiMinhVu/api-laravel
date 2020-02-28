<?php

namespace App\Http\Controllers;

use App\Models\{Manufacturer, ProductRegistration};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductRegistered;
use App\Http\Resources\ProductRegistration as ProductRegistrationResource;

class ProductRegistrationController extends Controller
{
    private $validationRules = [
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

    public function __invoke(Request $request, $manufacturer){

        $productRegistration = ProductRegistration::orderBy('id', 'DESC')->first();

        // Mail::to($productRegistration->email)->send(new ProductRegistered($productRegistration));

        return (new ProductRegistrationResource($productRegistration));

        if($this->validate($request, $this->validationRules)) {
            $data = [
                'form_site' => Manufacturer::siteByKey($manufacturer)
            ];
            $data = array_merge($request->all(), $data);

            $productRegistration = ProductRegistration::create($data);

            // Mail::to($productRegistration->email)->send(new ProductRegistered($productRegistration));

            return $productRegistration;
        }
    return false;
    }
}
