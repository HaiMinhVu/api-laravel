<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Brand extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $manufacturer = optional($this->manufacturer);
        return [
            'name' => $this->label,
            'prefix' => $this->prefix,
            'url' => $this->url,
            'site_list_id' => $this->id,
            'brand_id' => $manufacturer->id,
            'brand_name' => $manufacturer->name,
            'brand_slug' => $manufacturer->slug,
            'active' => $manufacturer->manufacture_active
        ];
    }
}
