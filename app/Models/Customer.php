<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'gst_number'
    ];

    public function contactPersons()
    {
        return $this->hasMany(ContactPerson::class);
    }
}
    