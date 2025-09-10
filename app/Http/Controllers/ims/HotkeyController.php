<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;
use App\Models\Hotkey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HotkeyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hotkeys = Hotkey::where('user_id', Auth::id())
                          ->orderBy('action_name')
                          ->get();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'hotkeys' => $hotkeys
            ]);
        }
        
        return view('ims.hotkeys.index', compact('hotkeys'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'action_name' => 'required|string|max:255',
            'hotkey_combination' => [
                'required',
                'string',
                'max:50',
                Rule::unique('hotkeys')->where('user_id', Auth::id())
            ],
            'description' => 'nullable|string|max:500',
            'action_url' => 'nullable|string|max:255',
            'action_type' => 'required|in:navigate,modal,function'
        ]);

        // Validate hotkey combination format
        if (!Hotkey::isValidCombination($validated['hotkey_combination'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid hotkey combination format.'
            ], 422);
        }

        $hotkey = Hotkey::create([
            'user_id' => Auth::id(),
            ...$validated
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hotkey created successfully.',
                'hotkey' => $hotkey
            ]);
        }

        return redirect()->route('hotkeys.index')->with('success', 'Hotkey created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotkey $hotkey)
    {
        // Ensure user owns this hotkey
        if ($hotkey->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'action_name' => 'required|string|max:255',
            'hotkey_combination' => [
                'required',
                'string',
                'max:50',
                Rule::unique('hotkeys')->where('user_id', Auth::id())->ignore($hotkey->id)
            ],
            'description' => 'nullable|string|max:500',
            'action_url' => 'nullable|string|max:255',
            'action_type' => 'required|in:navigate,modal,function'
        ]);

        // Validate hotkey combination format
        if (!Hotkey::isValidCombination($validated['hotkey_combination'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid hotkey combination format.'
            ], 422);
        }

        $hotkey->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hotkey updated successfully.',
                'hotkey' => $hotkey
            ]);
        }

        return redirect()->route('hotkeys.index')->with('success', 'Hotkey updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotkey $hotkey)
    {
        // Ensure user owns this hotkey
        if ($hotkey->user_id !== Auth::id()) {
            abort(403);
        }

        $hotkey->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hotkey deleted successfully.'
            ]);
        }

        return redirect()->route('hotkeys.index')->with('success', 'Hotkey deleted successfully.');
    }

    /**
     * Toggle hotkey active status
     */
    public function toggle(Hotkey $hotkey)
    {
        // Ensure user owns this hotkey
        if ($hotkey->user_id !== Auth::id()) {
            abort(403);
        }

        $hotkey->update([
            'is_active' => !$hotkey->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hotkey status updated.',
            'is_active' => $hotkey->is_active
        ]);
    }

    /**
     * Get user's active hotkeys for JavaScript
     */
    public function active()
    {
        $hotkeys = Hotkey::where('user_id', Auth::id())
                          ->where('is_active', true)
                          ->select('hotkey_combination', 'action_name', 'action_type', 'action_url', 'description')
                          ->get()
                          ->keyBy('hotkey_combination');

        return response()->json($hotkeys);
    }

    /**
     * Get available actions for dropdown
     */
    public function getActions()
    {
        $actions = [
            ['name' => 'Dashboard', 'url' => route('dashboard'), 'type' => 'navigate'],
            ['name' => 'Calendar', 'url' => route('calendar.index'), 'type' => 'navigate'],
            ['name' => 'Hotkey Manager', 'url' => route('hotkeys.index'), 'type' => 'navigate'],
            ['name' => 'Search', 'url' => '#', 'type' => 'modal'],
            ['name' => 'Settings', 'url' => '/settings', 'type' => 'navigate'],
            ['name' => 'Profile', 'url' => '/profile', 'type' => 'navigate'],
            ['name' => 'Help', 'url' => '/help', 'type' => 'navigate'],
            ['name' => 'Logout', 'url' => route('logout'), 'type' => 'function'],
        ];

        return response()->json($actions);
    }

    /**
     * Bulk delete hotkeys
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:hotkeys,id'
        ]);

        $deletedCount = Hotkey::where('user_id', Auth::id())
                              ->whereIn('id', $validated['ids'])
                              ->delete();

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} hotkey(s).",
            'deleted_count' => $deletedCount
        ]);
    }

    /**
     * Bulk activate hotkeys
     */
    public function bulkActivate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:hotkeys,id'
        ]);

        $updatedCount = Hotkey::where('user_id', Auth::id())
                              ->whereIn('id', $validated['ids'])
                              ->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => "Successfully activated {$updatedCount} hotkey(s).",
            'updated_count' => $updatedCount
        ]);
    }

    /**
     * Bulk deactivate hotkeys
     */
    public function bulkDeactivate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:hotkeys,id'
        ]);

        $updatedCount = Hotkey::where('user_id', Auth::id())
                              ->whereIn('id', $validated['ids'])
                              ->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => "Successfully deactivated {$updatedCount} hotkey(s).",
            'updated_count' => $updatedCount
        ]);
    }
}
