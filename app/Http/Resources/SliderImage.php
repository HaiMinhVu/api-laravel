<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderImage extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $fileManager = $this->fileManager;

        return [
            'description' => $this->description,
            'text' => $this->text,
            'url' => $fileManager->url(),
            'remote_path' => $fileManager->s3FilePath()
        ];
    }
}
