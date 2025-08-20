<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [ 'mailable','subject','to','success','error','meta' ];
    protected $casts = [ 'success'=>'boolean','meta'=>'array' ];
}
