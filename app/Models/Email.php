<?php

// app/Models/Email.php

namespace App\Models;

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
    ];

    protected $casts = [
        'attachments' => 'array',
    ];
}
