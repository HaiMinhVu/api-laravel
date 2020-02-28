<?php

namespace App\Http\Controllers;

use App\Models\{ ContactUs, Manufacturer };
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    private $validationRules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'zip' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
        'message' => 'required'
    ];

    public function __invoke(Request $request, $manufacturer){
        if($this->validate($request, $this->validationRules)) {
            $data = [
                'form_site' => Manufacturer::siteByKey($manufacturer)
            ];
            $data = array_merge($request->all(), $data);

            $contact = ContactUs::create($request->all());
            return $contact;
        }
        return false;
    }
}
