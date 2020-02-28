<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRegistration extends Model{

    protected $table = 'form_product_registration';

    protected $fillable = [
        'first_name',
        'last_name',
        'zip',
        'phone_number',
        'email',
        'address1',
        'address2',
        'city',
        'state',
        'product_id',
        'proof_of_purchase',
        'DealerStore',
        'price_paid',
        'date_purchased',
        'satisfaction',
        'serial_number',
        'form_site',
        'comments',
        'registration_type'
    ];

    const CREATED_AT = 'date_created';

    const UPDATED_AT = 'date_modified';

    protected $with = ['product'];

    public function customerName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function fullAddress()
    {
        return "{$this->address1} {$this->city}, {$this->state} {$this->zip}";
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
