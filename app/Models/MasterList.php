<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterList extends Model
{
    protected $table='master_list';

    public $timestamps = false;

}
