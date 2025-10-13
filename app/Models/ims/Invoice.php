<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
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
        'total',
        'courier_charges',
        'grand_total'
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

    /**
     * Get the payments associated with the invoice.
     */
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
}
