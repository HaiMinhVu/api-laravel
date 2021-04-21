<?php

namespace App\Http\Resources;

class ProductWithRelations extends Product
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $initialData = $this->initialData();
        $images = collect([]);
        if($initialData['main_image']) {
            $mainImage = [
                'id' => $initialData['main_image_id'],
                'order' => null,
                'url' => $initialData['main_image'],
                'remote_path' => $this->mainImage()->first()->s3FilePath()
            ];
            $images->push($mainImage);
        }

        if($this->images) {
            $imagesRelation = $this->images->filter(function($image) use ($images, $initialData) {
                return $image->fileManager()->exists() && ($images->first()['remote_path'] != $image->fileManager->s3FilePath());
                return $image->fileManager()->exists() && $image->fileManager->s3FilePath() != $images->first()['remote_path'];
            })->map(function($image) use ($images) {
                return [
                    'id' => $image->id,
                    'order' => $image->img_order,
                    'url' => $image->fileManager->url(),
                    'remote_path' => $image->fileManager->s3FilePath()
                ];
            })->sortBy('order')->toArray();
            $images = $images->merge($imagesRelation);
        }

        $downloads = optional(collect($this->manuals)->merge($this->specSheets))->map(function($download){
            return [
                'name' => $download->file_name,
                'display_name' => $download->display_name,
                'url' => $download->url(),
                'remote_path' => $download->s3FilePath()
            ];
        });

        $data = [
            'images' => $images->toArray(),
            'battery' => [
                'type' => $this->battery ? $this->battery['list']['type'] : '',
                'quantity' => $this->battery ? $this->battery['battery_qty'] : ''
            ],
            'features' => $this->features->map(function($feature){
                return trim($feature->feat_item);
            }),
            'specs' => $this->specs->map(function($spec){
                return [
                    'name' => $spec->list->utf8Convert('name'),
                    'suffix' => $spec->suffix,
                    'value' => $spec->description
                ];
            }),
            'videos' => optional($this->videos)->map(function($video){
                return [
                    'description' => $video->description,
                    'url' => $video->url(),
                ];
            }),
            'included_items' => optional($this->includedItems)->map(function($item){
                return $item->included_items;
            }),
            'related_products' => (new ProductCollection($this->relatedProducts)),
            'downloads' => $downloads
        ];
        return array_merge($initialData, $data);
    }
}
