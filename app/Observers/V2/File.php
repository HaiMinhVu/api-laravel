<?php

namespace App\Observers\V2;

use App\Models\V2\File as FileModel;

class File
{
    /**
    * Handle the FormSubmission "creating" event.
    *
    * @param  \App\Models\V2\File  $file
    * @return void
    */
    public function created(FileModel $file)
    {
        $file->name = str_replace(' ', '_', $file->name);
    }

}
