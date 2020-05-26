<?php

namespace App\Http\Resources\Crud;

use Illuminate\Http\Resources\Json\JsonResource;
use S3;

class FileListItem extends JsonResource
{
    /**
     * @var
     */
    private $imageWidth;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $imageWidth = 100)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->imageWidth = $imageWidth;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        try {
            $s3FilePath = $this->s3FilePath();
            $height = $this->imageWidth;
            $imageUrl = S3::imageLink($s3FilePath, $this->imageWidth, [
                'height' => $height,
                'background' => [
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'alpha' => 1
                ]
            ]);
        } catch(\Exception $e) {
            $imageUrl = null;
        }

        return [
            'id' => $this->ID,
            'file_name' => $this->file_name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'image' => $imageUrl
        ];
    }
}
