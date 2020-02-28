<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{

    protected function initialData()
    {
        $keywords = explode(',', $this->keywords);
        $keywords = array_map(function($keyword){
            $trimmed = trim($keyword);
            return ucfirst($trimmed);
        }, $keywords);

        // dd($this);

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'nsid' => $this->nsid,
            'name' => $this->Name,
            'description' => $this->feature_desc,
            'feature_name' => $this->feature_name,
            'main_image' => optional($this->mainImage()->first())->url(),
            'main_image_id' => optional($this->mainImage()->first())->id,
            'remote_image_path' => optional($this->mainImage()->first())->s3FilePath(),
            'manufacture' => $this->manufacture,
            'keywords' => $keywords,
            'upc' => $this->UPC,
            'total_quantity_on_hand' => $this->netsuiteProduct->total_quantity_on_hand,
            'price' => $this->netsuiteProduct->onlineprice,
            'in_stock' => ($this->netsuiteProduct->total_quantity_on_hand > 0)
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->initialData();
    }
}
