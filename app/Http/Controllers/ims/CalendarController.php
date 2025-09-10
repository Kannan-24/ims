<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(): View
    {
        return view('ims.calendar.index');
    }

    public function stats(): JsonResponse
    {
        $userId = Auth::id();
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_events' => Event::where('created_by', $userId)->count(),
            'today_events' => Event::where('created_by', $userId)
                ->whereDate('start', $today)
                ->count(),
            'upcoming_events' => Event::where('created_by', $userId)
                ->where('start', '>', Carbon::now())
                ->where('status', 'pending')
                ->count(),
            'completed_events' => Event::where('created_by', $userId)
                ->where('status', 'completed')
                ->count(),
            'this_week_events' => Event::where('created_by', $userId)
                ->where('start', '>=', $thisWeek)
                ->where('start', '<', $thisWeek->copy()->addWeek())
                ->count(),
            'this_month_events' => Event::where('created_by', $userId)
                ->where('start', '>=', $thisMonth)
                ->where('start', '<', $thisMonth->copy()->addMonth())
                ->count(),
        ];

        return response()->json($stats);
    }

    public function events(Request $request): JsonResponse
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $events = Event::with(['creator', 'updater'])
            ->whereBetween('start', [$start, $end])
            ->orWhereBetween('end', [$start, $end])
            ->get();

        return response()->json(
            $events->map(fn($event) => $event->toFullCalendarArray())
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'type' => 'required|string|in:meeting,task,appointment,reminder,other',
            'status' => 'nullable|string|in:pending,in_progress,completed,cancelled',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'all_day' => 'boolean'
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['color'] = $validated['color'] ?? $this->getTypeColor($validated['type']);

        $event = Event::create($validated);

        return response()->json($event->toFullCalendarArray(), 201);
    }

    public function show(Event $event): JsonResponse
    {
        // Ensure user can only access their own events
        if ($event->created_by !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $event->load(['creator', 'updater']);
        return response()->json($event->toFullCalendarArray());
    }

    public function update(Request $request, Event $event): JsonResponse
    {
        // Ensure user can only update their own events
        if ($event->created_by !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start' => 'sometimes|date',
            'end' => 'sometimes|date|after:start',
            'type' => 'sometimes|string|in:meeting,task,appointment,reminder,other',
            'status' => 'sometimes|in:pending,completed,cancelled',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'all_day' => 'boolean'
        ]);

        $validated['updated_by'] = Auth::id();

        $event->update($validated);

        return response()->json($event->fresh()->toFullCalendarArray());
    }

    public function destroy(Event $event): JsonResponse
    {
        // Ensure user can only delete their own events
        if ($event->created_by !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }

    public function move(Request $request, Event $event): JsonResponse
    {
        // Ensure user can only move their own events
        if ($event->created_by !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        $validated['updated_by'] = Auth::id();
        $event->update($validated);

        return response()->json($event->fresh()->toFullCalendarArray());
    }

    private function getTypeColor(string $type): string
    {
        return match ($type) {
            'meeting' => '#4f46e5',      // Indigo
            'task' => '#06b6d4',         // Cyan
            'appointment' => '#10b981',   // Emerald
            'reminder' => '#f59e0b',      // Amber
            'other' => '#6b7280',        // Gray
            default => '#4f46e5'
        };
    }
}
