<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'phone_no',
        'email',
    ];

    protected $table = 'contact_persons'; // Specify the table name

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
