<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    protected $fillable = ['payment_id', 'amount', 'payment_date', 'reference_number', 'payment_method'];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
