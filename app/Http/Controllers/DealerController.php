<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dealer;

class DealerController extends Controller
{
    public function index()
    {
        return Dealer::all()->map(function($dealer){
            return $dealer->fileManager->url();
        });
    }
}
