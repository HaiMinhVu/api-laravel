<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class Product extends JsonResource
{

    /**
     * @OA\Schema(
     *     title="Product",
     *     description="Product resource",
     *     schema="ProductResource",
     *     @OA\Xml(
     *         name="Product"
     *     )
     * )
     */

    protected function initialData()
    {
        $keywords = explode(',', $this->keywords);
        $keywords = array_map(function($keyword){
            $trimmed = trim($keyword);
            return ucfirst($trimmed);
        }, $keywords);

        $description = trim($this->feature_desc);
        $description = strip_tags($description, "<a><br><div><table><tr><td><p><img><ul><ol><li>");

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'nsid' => $this->nsid,
            'name' => trim($this->Name),
            'description' => $description,
            'feature_name' => trim($this->feature_name),
            'main_image' => optional($this->mainImage()->first())->url(),
            'main_image_id' => optional($this->mainImage()->first())->id,
            'remote_image_path' => optional($this->mainImage()->first())->s3FilePath(),
            'manufacture' => $this->manufacture,
            'keywords' => $keywords,
            'upc' => $this->UPC,
            'total_quantity_on_hand' => $this->netsuiteProduct->total_quantity_on_hand,
            'price' => $this->netsuiteProduct->onlineprice,
            'in_stock' => ($this->netsuiteProduct->total_quantity_on_hand > 0),
            'last_remote_update' => Carbon::parse($this->netsuiteProduct->updated_at)->setTimezone('UTC')->format('c'),
            'upc' => $this->UPC,
            'allow_backorders' => $this->allow_backorders
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
