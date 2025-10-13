<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
        'name',
        'description',
        'hsn_code',
        'gst_percentage',
        'unit_type',
        'is_igst', // New field to determine GST/IGST
    ];

    public function productSuppliers()
    {
        return $this->hasMany(ProductSupplier::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers');
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function service()
    {
        return $this->hasMany(Service::class);
    }


    
}
