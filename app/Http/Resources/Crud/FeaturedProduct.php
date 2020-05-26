<?php

namespace App\Http\Resources\Crud;

use Illuminate\Http\Resources\Json\JsonResource;

class FeaturedProduct extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'description' => $this->description,
            'products' => new FeaturedProductListItemCollection($this->children)
        ];
    }
}
