<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
