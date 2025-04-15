<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log($actionType, $module, $description = null)
    {
        ActivityLog::create([
            'user_type' => Auth::check() ? Auth::user()->role : 'System',
            'user_id' => Auth::id(),
            'action_type' => $actionType,
            'module' => $module,
            'description' => $description,
            'ip_address' => Request::ip(),
        ]);
    }
}
