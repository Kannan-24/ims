<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
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
        'quotation_id',
        'product_id',
        'service_id',
        'type', // 'product' or 'service'
        'unit_type',
        'quantity',
        'unit_price',
        'discount_percentage',
        'discount_amount',
        'taxable_amount',
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
