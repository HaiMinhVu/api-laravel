<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class Form extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $fields = $this->fields->map(function($field){
            $data = $field->toArray();
            $data['type'] = $data['type']['name'];
            return $data;
        });
        return [
            'id' => $this->id,
            'fields' => $fields
        ];
    }
}
