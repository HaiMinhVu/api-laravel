<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model{

    protected $table='form_contact_us';

    protected $fillable = ['first_name','last_name','zip','phone','email','message'];

    public $timestamps = false;
}
