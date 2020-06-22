<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\FileManager;

class ProductCategory extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'label' => $this->label,
            'parent' => $this->parent,
            'image' => $this->imageUrl(),
            'remote_path' => optional($this->fileManager)->s3FilePath(),
            'product_count' => $this->products()->count()
        ];

        if($this->relationLoaded('subCategories')) {
            $data['subCategories'] = new ProductCategoryCollection($this->subCategories);
        }

        return $data;
    }
}
