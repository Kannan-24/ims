<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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
        'cid',
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    
}
    