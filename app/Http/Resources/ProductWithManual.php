<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductWithManual extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $url = $this->resource->getS3Url();

        $data = [
            'id' => $this->ID,
            'file_name' => $this->file_name,
            'display_name' => $this->display_name,
            // 'description' => $this->description,
            'brand' => $this->resource->siteList->label,
            'url' => $url,
            // 'raw_url' =>  $this->resource->getS3Url(),
        ];

        if(count($this->manuals)){
            $data['sku'] = $this->manuals[0]->product ? $this->manuals[0]->product->sku : '';
        }
        else{
            $data['sku'] = '';
        }


        if($this->resource->isType('manual')) {

            $languages = $this->resource->load(['manuals.languages', 'manuals' => function($q){
                $q->whereHas('languages');
            }])->manuals->map(function($manual){
                return $manual->languages->map(function($language){
                    return $language->description;
                });
            })->flatten(1)->unique();

            if(count($languages)){
                $data['languages'] = implode(", ", json_decode(json_encode($languages), 1));
            }
            else{
                $data['languages'] = '';
            }
            
        }

        return $data;
    }
}
