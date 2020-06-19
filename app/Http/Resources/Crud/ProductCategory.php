<?php

namespace App\Http\Resources\Crud;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id' => $this->id,
            'label' => $this->label,
            'sub_categories' => new ProductCategoryCollection($this->subCategories),
            'product_count' => $this->products()->count()
        ];
        // return parent::toArray($request);
    }
}
