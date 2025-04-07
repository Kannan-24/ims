<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'product_id',
        'service_id',
        'type', // 'product' or 'service'
        'unit_type',
        'quantity',
        'unit_price',
        'total',
        'cgst',
        'sgst',
        'igst',
        'gst' // Combined GST
    ];

    /**
     * Get the quotation that owns the item.
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Get the product associated with the quotation item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
