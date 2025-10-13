<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
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
     * Get the contact person associated with the quotation.
     */
    public function contactPerson()
    {
        return $this->belongsTo(ContactPerson::class, 'contactperson_id');
    }

    /**
     * Get the items associated with the quotation.
     */
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
