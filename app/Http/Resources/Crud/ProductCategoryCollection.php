<?php

namespace App\Http\Resources\Crud;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCategoryCollection extends ResourceCollection
{
    /**
    * The resource that this resource collects.
    *
    * @var string
    */
   public $collects = 'App\Http\Resources\Crud\ProductCategory';
}
