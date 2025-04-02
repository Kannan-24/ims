<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'order_date',
        'order_no',
        'customer_id',
        'contactperson_id',
        'sub_total',
        'cgst',
        'sgst',
        'igst',
        'gst',
        'total'
    ];

    /**
     * Get the customer associated with the invoice.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the contact person associated with the invoice.
     */
    public function contactPerson()
    {
        return $this->belongsTo(ContactPerson::class, 'contactperson_id');
    }

    /**
     * Get the items associated with the invoice.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the stock associated with the invoice.
     */
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
