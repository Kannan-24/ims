<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_code',
        'quotation_date',
        'customer_id',
        'contactperson_id',
        'sub_total',
        'cgst',
        'sgst',
        'igst',
        'gst',
        'total',
        'terms_condition'
    ];

    /**
     * Get the customer associated with the quotation.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the items associated with the quotation.
     */
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
