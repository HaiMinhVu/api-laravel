<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ScriptController extends Controller
{

	const BASE_NETSUITE_ITEMSTOCKURL = 'https://checkout.na1.netsuite.com/app/site/query/getitemstockstatus.nl?c=1247539&n=1&outofstocktext=Out%20of%20stock&instocktext=In%20stock';

    public function getStatus(Request $request, $id)
    {
    	return $this->cacheResponse($request, function() use ($id) {
	    	$url = self::BASE_NETSUITE_ITEMSTOCKURL.'&id='.$id;
	    	return $this->getExternalUrlContents($url);
	    });
    }

    public function getExternalUrl(Request $request, $encodedUrl)
    {
    	return $this->cacheResponse($request, function() use ($encodedUrl, $request) {
            $encodedUrl = str_replace('_', '/', $encodedUrl);
    		$url = base64_decode($encodedUrl);
    		$contents = $this->getExternalUrlContents($url);
            return response($contents);
    	});
    }

    public function getExternalUrlFromParam(Request $request)
    {
        if($encodedUrl = $request->get('url')) {
            return $this->cacheResponse($request, function() use ($encodedUrl) {
                $url = base64_decode($encodedUrl);
                $contents = $this->getExternalUrlContents($url);
                return response($contents);
            });
        }
        return null;
    }

    private function getExternalUrlContents($url) {
    	$client = new Client;
    	$res = $client->get($url);
    	return $res->getBody()->getContents();
    }

}
