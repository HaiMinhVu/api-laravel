<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| File Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function(){
	abort(404);
});
Route::get('download/{key?}', 'FileController@download')->where('key', '(.*)');
Route::get('{key?}', 'FileController@stream')->where('key', '(.*)')->name('file-view');
