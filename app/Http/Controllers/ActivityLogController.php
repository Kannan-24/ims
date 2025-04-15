<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user'); // Eager load user

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('action_type', 'like', '%' . $request->search . '%')
                    ->orWhere('module', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('user_type', 'like', '%' . $request->search . '%')
                    ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->latest()->paginate(15);

        return view('activity_logs.index', compact('logs'));
    }


    public function show($id)
    {
        $log = ActivityLog::findOrFail($id);
        return view('activity_logs.show', compact('log'));
    }


    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return redirect()->route('activity-logs.index')->with('success', 'Log deleted successfully.');
    }

    public function clearAll()
    {
        ActivityLog::truncate();

        return redirect()->route('activity-logs.index')->with('success', 'All logs cleared successfully.');
    }
}
