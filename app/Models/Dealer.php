<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $table='dealer_list';

    public $timestamps = false;

    protected $with = ['fileManager'];

    public function fileManager()
    {
        return $this->belongsTo(FileManager::class, 'file_id', 'ID');
    }
}
