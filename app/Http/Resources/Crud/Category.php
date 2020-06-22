<?php

namespace App\Http\Resources\Crud;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'parent_id' => $this->parent,
            'name' => $this->label,
            'text' => $this->pc_text,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'thumbnail_id' => $this->thumbnail,
            'brand_id' => $this->manufacture
        ];
    }
}
