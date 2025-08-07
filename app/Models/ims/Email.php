<?php

// app/Models/Email.php
namespace App\Models\ims;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'to',
        'cc',
        'bcc',
        'subject',
        'body',
        'attachments',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'sent_at' => 'datetime',
    ];

    // Scopes
    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }
}
