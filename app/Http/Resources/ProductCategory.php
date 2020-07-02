<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\FileManager;
use Carbon\Carbon;

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
            'product_count' => $this->products()->count(),
            'last_update' => Carbon::parse($this->date_modified)->setTimezone('UTC')->format('c')
        ];

        if($this->relationLoaded('subCategories')) {
            $data['subCategories'] = new ProductCategoryCollection($this->subCategories);
        }

        return $data;
    }
}
