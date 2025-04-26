<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ims\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Fetch distinct modules and their log count
        $modules = ActivityLog::selectRaw('module, COUNT(*) as count')
            ->groupBy('module')
            ->get();

        // If module filter is applied
        $logs = ActivityLog::with('user')
            ->when($request->filled('module'), function ($query) use ($request) {
                $query->where('module', $request->module);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('action_type', 'like', '%' . $request->search . '%')
                        ->orWhere('user_type', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('ims.activity_logs.index', compact('modules', 'logs'));
    }

    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        return view('ims.activity_logs.show', compact('log'));
    }

    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();
        return redirect()->route('activity-logs.index')->with('success', 'Log deleted successfully.');
    }


    public function destroyModule($module)
    {
        ActivityLog::where('module', $module)->delete();

        return redirect()->route('activity-logs.index')->with('success', "All logs for module '{$module}' have been cleared.");
    }

    public function destroyAll()
    {
        ActivityLog::truncate();
        return redirect()->route('activity-logs.index')->with('success', 'All logs have been cleared.');
    }

}
