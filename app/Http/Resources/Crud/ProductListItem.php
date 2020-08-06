<?php

namespace App\Http\Resources\Crud;

use Illuminate\Http\Resources\Json\JsonResource;
use S3;

class ProductListItem extends JsonResource
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
    public function __construct($resource, $imageWidth = 40)
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
            $s3FilePath = optional($this->mainImage)->s3FilePath();
            if($s3FilePath) {
                $height = $this->imageWidth;
                $imageUrl = S3::imageLink($s3FilePath, 40, [
                    'height' => $height,
                    'background' => [
                        'r' => 255,
                        'g' => 255,
                        'b' => 255,
                        'alpha' => 1
                    ]
                ]);
            } else {
                $imageUrl = null;
            }
        } catch(\Exception $e) {
            $imageUrl = null;
        }

        return [
            'id' => $this->id,
            'nsid' => $this->nsid,
            'name' => $this->Name,
            'sku' => $this->sku,
            'image' => $imageUrl
        ];
    }
}
