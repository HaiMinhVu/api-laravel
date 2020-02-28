<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NavCat;

class NavController extends Controller
{
    public function show(Request $request, $id)
    {
        return optional(NavCat::find($id))->items;
    }
}
