<?php

namespace App\Http\Resources\Crud;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FeaturedProductListItemCollection extends ResourceCollection
{
     /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = 'App\Http\Resources\Crud\FeaturedProductListItem';
}
