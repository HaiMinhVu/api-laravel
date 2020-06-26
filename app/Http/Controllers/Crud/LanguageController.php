<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Language::all()->map(function($language){
            return [
                'id' => $language->id,
                'value' => $language->description
            ];
        });
        return response()->json(['data' => $data]);
    }

}
