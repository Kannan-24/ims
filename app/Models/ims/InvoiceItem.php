<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
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
        'invoice_id',
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
     * Get the invoice that owns the item.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the product associated with the invoice item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the service associated with the invoice item.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
