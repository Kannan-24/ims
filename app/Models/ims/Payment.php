<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['invoice_id', 'total_amount', 'status'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(PaymentItem::class);
    }

    // In Payment model
    public function paymentItems()
    {
        return $this->hasMany(PaymentItem::class);  // Adjust with correct class name if needed
    }
}
