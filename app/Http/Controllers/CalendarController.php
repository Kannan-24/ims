<?php

namespace App\Http\Controllers;

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
        return view('calendar.index');
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
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'all_day' => 'boolean'
        ]);

        $validated['created_by'] = Auth::id();
        $validated['color'] = $validated['color'] ?? $this->getTypeColor($validated['type']);

        $event = Event::create($validated);

        return response()->json($event->toFullCalendarArray(), 201);
    }

    public function show(Event $event): JsonResponse
    {
        $event->load(['creator', 'updater']);
        return response()->json($event->toFullCalendarArray());
    }

    public function update(Request $request, Event $event): JsonResponse
    {
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
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }

    public function move(Request $request, Event $event): JsonResponse
    {
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
