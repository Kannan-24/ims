<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ims\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Display the chat interface
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->select('id', 'name', 'email', 'profile_photo', 'role', 'phone', 'employee_id')
            ->get();

        return view('ims.chat.index', compact('users'));
    }

    /**
     * Chat with specific user
     */
    public function chatWithUser(User $user)
    {
        $users = User::where('id', '!=', Auth::id())
            ->select('id', 'name', 'email', 'profile_photo', 'role')
            ->get();

        return view('ims.chat.index', compact('users', 'user'));
    }

    /**
     * Get messages for professional chat interface (Secure)
     */
    public function getMessages(User $user)
    {
        $currentUserId = Auth::id();

        $messages = Message::betweenUsers($currentUserId, $user->id)
            ->with(['sender:id,name,profile_photo', 'receiver:id,name,profile_photo'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read (only messages sent TO the current user)
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // Format messages for the frontend
        $formattedMessages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'created_at' => $message->created_at->toISOString(),
                'read_at' => $message->read_at ? $message->read_at->toISOString() : null,
            ];
        });

        return response()->json($formattedMessages);
    }

    /**
     * Get chat history between current user and another user (Secure)
     */
    public function getChatHistory(User $user)
    {
        $currentUserId = Auth::id();

        $messages = Message::betweenUsers($currentUserId, $user->id)
            ->with(['sender:id,name,profile_photo', 'receiver:id,name,profile_photo'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read (only messages sent TO the current user)
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'user' => $user
        ]);
    }

    /**
     * Send a new message with enhanced security and attachment support
     */
    public function sendMessage(Request $request)
    {
        $currentUserId = Auth::id();

        // Enhanced validation
        $request->validate([
            'receiver_id' => 'required|exists:users,id|different:' . $currentUserId,
            'message' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,xlsx,xls,ppt,pptx,zip,rar'
        ]);

        // Security: Prevent sending messages to yourself
        if ($request->receiver_id == $currentUserId) {
            return response()->json([
                'success' => false,
                'error' => 'Cannot send message to yourself'
            ], 400);
        }

        // Require either message or attachment
        if (empty($request->message) && !$request->hasFile('attachment')) {
            return response()->json([
                'success' => false,
                'error' => 'Message or attachment is required'
            ], 400);
        }

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();

            // Log file details for debugging
            Log::info('File upload attempt:', [
                'original_name' => $attachmentName,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            // Generate unique filename to prevent conflicts
            $fileName = time() . '_' . $currentUserId . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $attachmentName);
            $attachmentPath = $file->storeAs('chat-attachments', $fileName, 'public');

            Log::info('File stored at:', ['path' => $attachmentPath]);
        }

        $message = Message::create([
            'sender_id' => $currentUserId,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message ?? '',
            'attachment' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_read' => false
        ]);

        $message->load(['sender:id,name,profile_photo', 'receiver:id,name,profile_photo']);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get users with their last message and unread count (Secure)
     */
    public function getUsersWithMessages()
    {
        $currentUserId = Auth::id();

        // Get all users except current user
        $users = User::where('id', '!=', $currentUserId)
            ->select('id', 'name', 'email', 'profile_photo', 'role', 'phone', 'created_at', 'employee_id')
            ->get();

        $usersWithMessages = $users->map(function ($user) use ($currentUserId) {
            // Get last message between current user and this user (secure)
            $lastMessage = Message::betweenUsers($currentUserId, $user->id)
                ->latest()
                ->first();

            // Get unread count from this user to current user only
            $unreadCount = Message::where('sender_id', $user->id)
                ->where('receiver_id', $currentUserId)
                ->where('is_read', false)
                ->count();

            // Check if user is online (you can implement your own logic)
            $user->online = true; // For demo purposes, set all users as online

            $user->last_message = $lastMessage?->message ?? null;
            $user->last_message_time = $lastMessage?->created_at ?? null;
            $user->unread_count = $unreadCount;

            return $user;
        });

        return response()->json($usersWithMessages);
    }

    /**
     * Download attachment with security checks
     */
    public function downloadAttachment(Message $message)
    {
        $currentUserId = Auth::id();

        // Security: Only sender or receiver can download attachments
        if ($message->sender_id !== $currentUserId && $message->receiver_id !== $currentUserId) {
            abort(403, 'Unauthorized access to attachment');
        }

        if (!$message->attachment) {
            abort(404, 'Attachment not found');
        }

        $filePath = storage_path('app/public/' . $message->attachment);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $message->attachment_name ?? 'attachment');
    }

    /**
     * Mark messages as read with security
     */
    public function markAsRead(User $user)
    {
        $currentUserId = Auth::id();

        // Only mark messages sent TO the current user as read
        $updated = Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'marked_count' => $updated
        ]);
    }

    /**
     * Typing indicator with security
     */
    public function typing(Request $request)
    {
        $currentUserId = Auth::id();

        $request->validate([
            'receiver_id' => 'required|exists:users,id|different:' . $currentUserId,
            'is_typing' => 'required|boolean'
        ]);

        // Here you would broadcast typing status to the receiver only
        // broadcast(new UserTyping($currentUserId, $request->receiver_id, $request->is_typing))->toOthers();

        return response()->json(['success' => true]);
    }

    /**
     * Get new messages with security (for real-time updates)
     */
    public function getNewMessages(Request $request)
    {
        $currentUserId = Auth::id();
        $lastMessageId = $request->get('last_message_id', 0);
        $withUserId = $request->get('with_user_id');

        // Security: Only get messages where current user is participant
        $query = Message::where('id', '>', $lastMessageId)
            ->where(function ($q) use ($currentUserId, $withUserId) {
                $q->where(function ($subQ) use ($currentUserId, $withUserId) {
                    $subQ->where('sender_id', $currentUserId)
                        ->where('receiver_id', $withUserId);
                })->orWhere(function ($subQ) use ($currentUserId, $withUserId) {
                    $subQ->where('sender_id', $withUserId)
                        ->where('receiver_id', $currentUserId);
                });
            });

        $messages = $query->with(['sender:id,name,profile_photo'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
            'typing_users' => [] // Implement real-time typing if needed
        ]);
    }
}
