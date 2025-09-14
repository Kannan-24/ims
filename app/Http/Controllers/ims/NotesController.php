<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Note::forUser(Auth::id())
                    ->with('creator')
                    ->orderByDesc('is_pinned')
                    ->orderByDesc('updated_at');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('pinned') && $request->pinned === 'true') {
            $query->pinned();
        }

        $notes = $query->paginate(12);
        
        $stats = [
            'total' => Note::forUser(Auth::id())->count(),
            'pinned' => Note::forUser(Auth::id())->pinned()->count(),
            'recent' => Note::forUser(Auth::id())->where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('ims.notes.index', compact('notes', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ims.notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_pinned' => 'boolean'
        ]);

        $note = Note::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_pinned' => $request->boolean('is_pinned'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('notes.index')->with('success', 'Note created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        // Ensure user can only view their own notes
        if ($note->created_by !== Auth::id()) {
            abort(403);
        }

        return view('ims.notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        // Ensure user can only edit their own notes
        if ($note->created_by !== Auth::id()) {
            abort(403);
        }

        return view('ims.notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        // Ensure user can only update their own notes
        if ($note->created_by !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_pinned' => 'boolean'
        ]);

        $note->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        return redirect()->route('notes.index')->with('success', 'Note updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        // Ensure user can only delete their own notes
        if ($note->created_by !== Auth::id()) {
            abort(403);
        }

        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Note deleted successfully!');
    }

    /**
     * Toggle pin status of a note.
     */
    public function togglePin(Note $note)
    {
        // Ensure user can only toggle their own notes
        if ($note->created_by !== Auth::id()) {
            abort(403);
        }

        $note->update(['is_pinned' => !$note->is_pinned]);

        $message = $note->is_pinned ? 'Note pinned successfully!' : 'Note unpinned successfully!';
        
        return response()->json(['success' => true, 'message' => $message, 'is_pinned' => $note->is_pinned]);
    }
}
