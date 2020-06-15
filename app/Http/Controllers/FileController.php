<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class FileController extends Controller
{

	const STORAGE_DISK = 's3';

	public function download(Request $request, string $key)
	{
		try {
			return Storage::disk('s3')->download($key, $this->getBasename($key), [
				'Content-Type' => $this->getContentFileType($key)
			]);
		} catch(\Exception $e) {
			abort(404);
		}
	}

	public function stream(Request $request, string $key)
	{
		try {
			return response()->make($this->getFromStorage($key), 200, [
			    'Content-Type' => $this->getContentFileType($key),
			    'Content-Disposition' => 'inline; filename="'.$this->getBasename($key).'"'
			]);
		} catch(\Exception $e) {
			abort(404);
		}
	}

	private function getFromStorage(string $key)
	{
		return $this->storage()->get($key);
	}

	private function getContentFileType(string $key)
	{
		$file = $this->getFromStorage($key);
		$finfo = new \finfo(FILEINFO_MIME);
		return $finfo->buffer($file);
	}

	private function getBasename(string $key)
	{
		$info = pathinfo($key);
		return $info['basename'];
	}

	private function storage()
	{
		return Storage::disk(self::STORAGE_DISK);
	}

}
