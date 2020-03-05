<?php

namespace App\Http\Controllers;

use App\Models\{Manufacturer, ProductRegistration};
use App\Http\Requests\ProductRegistrationRequest as Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductRegistered;
use App\Http\Resources\ProductRegistration as ProductRegistrationResource;

class ProductRegistrationController extends Controller
{
    public function __invoke(Request $request, $manufacturer){
        $data = [
            'form_site' => Manufacturer::siteByKey($manufacturer)
        ];
        $data = array_merge($request->all(), $data);

        $productRegistration = ProductRegistration::create($data);

        // Mail::to($productRegistration->email)->send(new ProductRegistered($productRegistration));

        return (new ProductRegistrationResource($productRegistration));
    return false;
    }
}
