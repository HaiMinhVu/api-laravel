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
        $image = ($this->fileManager) ? $this->fileManager->url() : FileManager::defaultImage();
        
        $data = [
            'id' => $this->id,
            'label' => $this->label,
            'parent' => $this->parent,
            'image' => $image
        ];

        if($this->relationLoaded('subCategories')) {
            $data['subCategories'] = new ProductCategoryCollection($this->subCategories);
        }

        return $data;
    }
}
