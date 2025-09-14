<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ims\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Display the chat interface
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
                    ->select('id', 'name', 'email', 'profile_photo', 'role')
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
     * Get chat history between current user and another user
     */
    public function getChatHistory(User $user)
    {
        $messages = Message::betweenUsers(Auth::id(), $user->id)
                          ->with(['sender:id,name,profile_photo', 'receiver:id,name,profile_photo'])
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
               ->where('receiver_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'user' => $user
        ]);
    }

    /**
     * Get messages for professional chat interface
     */
    public function getMessages(User $user)
    {
        $messages = Message::betweenUsers(Auth::id(), $user->id)
                          ->with(['sender:id,name,profile_photo', 'receiver:id,name,profile_photo'])
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
               ->where('receiver_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'lastMessageId' => $messages->last()?->id ?? 0
        ]);
    }

    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
            'attachment' => 'nullable|file|max:10240' // 10MB max
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('chat-attachments', 'public');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'is_read' => false
        ]);

        $message->load(['sender:id,name,profile_photo', 'receiver:id,name,profile_photo']);

        // Here you can broadcast the message using Laravel Echo/Pusher
        // broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get users with their last message and unread count
     */
    public function getUsersWithMessages()
    {
        $currentUserId = Auth::id();
        
        $users = User::where('id', '!=', $currentUserId)
                    ->select('id', 'name', 'email', 'profile_photo', 'role')
                    ->get();

        $usersWithMessages = $users->map(function ($user) use ($currentUserId) {
            // Get last message between current user and this user
            $lastMessage = Message::betweenUsers($currentUserId, $user->id)
                                 ->latest()
                                 ->first();

            // Get unread count from this user to current user
            $unreadCount = Message::where('sender_id', $user->id)
                                 ->where('receiver_id', $currentUserId)
                                 ->where('is_read', false)
                                 ->count();

            $user->last_message = $lastMessage;
            $user->unread_count = $unreadCount;
            
            return $user;
        });

        return response()->json($usersWithMessages);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(User $user)
    {
        Message::where('sender_id', $user->id)
               ->where('receiver_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get new messages (for polling)
     */
    public function getNewMessages(Request $request)
    {
        $lastMessageId = $request->get('last_message_id', 0);
        $withUserId = $request->get('with_user_id');

        $query = Message::where('id', '>', $lastMessageId)
                       ->where('receiver_id', Auth::id());

        if ($withUserId) {
            $query->where('sender_id', $withUserId);
        }

        $messages = $query->with(['sender:id,name,profile_photo'])
                         ->orderBy('created_at', 'asc')
                         ->get();

        return response()->json($messages);
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(Message $message)
    {
        if ($message->attachment) {
            $path = storage_path('app/public/chat-attachments/' . $message->attachment);
            if (file_exists($path)) {
                return response()->download($path);
            }
        }
        
        return response()->json(['error' => 'File not found'], 404);
    }

    public function typing(Request $request)
    {
        return response()->json(['success' => true]);
    }
}
