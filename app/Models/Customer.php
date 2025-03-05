<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'cid', 'name', 'contact_person', 'email', 'phone', 'address', 'city', 'state', 'zip', 'country', 'gstno',
    ];
}
