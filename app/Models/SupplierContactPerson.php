<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierContactPerson extends Model
{
    protected $table = 'supplier_contact_persons';
    
    protected $fillable = [
        'supplier_id',
        'name',
        'position',
        'phone',
        'email',
    ];

    /**
     * Get the supplier that owns the contact person.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(\App\Models\ims\Supplier::class);
    }
}
