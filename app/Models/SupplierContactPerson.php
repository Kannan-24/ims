<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierContactPerson extends Model
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
