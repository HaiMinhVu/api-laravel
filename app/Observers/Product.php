<?php

namespace App\Observers;

use App\Models\Product as ProductModel;

class Product
{
    /**
    * Handle the Product "saving" event.
    *
    * @param  \App\Models\Product  $product
    * @return void
    */
    public function saving(ProductModel $product)
    {
        if($product->images()->count() > 0 && !$product->mainImage()->exists()) {
            $product->main_img_id = $product->images()->first()->fileManager->id;
        }
    }
}
