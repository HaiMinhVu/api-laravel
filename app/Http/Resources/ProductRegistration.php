<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductRegistration extends JsonResource
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
            'customer_name' => $this->customerName(),
            'full_address' => $this->fullAddress(),
            'phone_number' => $this->phone_number,
            'sku' => $this->product->sku,
            'serial_number' => $this->serial_number,
            'dealer_store' => $this->DealerStore,
            'price_paid' => $this->price_paid,
            'date_purchased' => $this->date_purchased
        ];
    }
}
