<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory, HasUuids;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $fillable = [
        'supplier_id',
        'name',
        'company_name',
        'contact_person',
        'email',
        'phone_number',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'gst',
        'website',
    ];

    public function productSuppliers()
    {
        return $this->hasMany(ProductSupplier::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_suppliers')
                    ->withTimestamps();
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function contactPersons()
    {
        return $this->hasMany(\App\Models\SupplierContactPerson::class);
    }
}
