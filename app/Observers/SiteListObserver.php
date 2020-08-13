<?php

namespace App\Observers;

use App\Models\SiteList;
use Illuminate\Support\Str;

class SiteListObserver
{
    /**
    * Handle the SiteList "creating" event.
    *
    * @param  \App\Models\SiteList  $siteList
    * @return void
    */
    public function creating(SiteList $siteList)
    {
        $siteList->prefix = Str::slug($siteList->label);
    }
}
