<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FriendRequest;
use App\Models\Friendship;
use App\Models\Message;
use App\Models\Badge;
use App\Models\Block;

class UserController extends Controller
{
    public function index()
    {
        $currentUserId = Auth::id();
        $currentUser = User::findOrFail($currentUserId);
        $currentUserUniversity = $currentUser->university;
    
        $students = User::where('role', 'user')
                        ->whereNotNull('university')
                        ->where('university', $currentUserUniversity)
                        ->where('id', '!=', $currentUserId)
                        ->paginate(9);
    
        return view('view-students', [
            'students' => $students,
            'currentUserUniversity' => $currentUserUniversity, 
        ]);
    }
    
    public function showProfile($id)
    {
        $authId = auth()->id();
        $student = User::findOrFail($id);

        $isPendingRequest = FriendRequest::where('sender_id', auth()->id())
                                         ->where('receiver_id', $student->id)
                                         ->where('status', 'pending')
                                         ->exists();

        $isFriend = Friendship::where(function ($query) use ($student) {
        $query->where('user_id', Auth::id())
                ->where('friend_id', $student->id);
        })
        ->orWhere(function ($query) use ($student) {
            $query->where('user_id', $student->id)
                    ->where('friend_id', Auth::id());
        })
        ->where('status', 'accepted')
        ->exists();

        $badges = Badge::where('user_id', $id)->get();

        $isBlocked = Block::where('user_id', Auth::id())
                        ->where('blocked_id', $student->id)
                        ->exists();

        return view('student', [
            'student' => $student,
            'isPendingRequest' => $isPendingRequest,
            'isFriend' => $isFriend,
            'authId' => $authId,
            'badges' => $badges,
            'isBlocked' => $isBlocked,
        ]);
    }

    public function sendMessage(Request $request, $id)
    {
        $currentUserId = Auth::id();
    
        $message = new Message([
            'senderId' => $currentUserId,
            'receiverId' => $id,
            'message' => $request->input('messageField'),
            'read' => false,
            'deleted' => false,
        ]);
    
        $message->save();
    
        return redirect()->route('user.profile', ['id' => $id])->with('success', 'Message sent successfully.');
    }

    public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);
    
        $message->deleted = 1;
        $message->save();
    
        return redirect()->back()->with('success', 'Message deleted successfully!');
    }
    

    public function markMessage($id)
    {
        $message = Message::findOrFail($id);
        $message->update(['read' => 1]);

        return redirect()->back()->with('success', 'Message marked as read successfully.');
    }

    public function unmarkMessage($id)
    {
        $message = Message::findOrFail($id);
        $message->update(['read' => 0]);
        
        return redirect()->back()->with('success', 'Message marked as unread successfully.');
    }

    public function blockUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', 
            'blocked_id' => 'required|exists:users,id', 
        ]);
    
        $userId = $request->user_id;
        $blockedId = $request->blocked_id;
    
        Friendship::where(function ($query) use ($userId, $blockedId) {
            $query->where('user_id', $userId)
                ->where('friend_id', $blockedId);
        })->orWhere(function ($query) use ($userId, $blockedId) {
            $query->where('user_id', $blockedId)
                ->where('friend_id', $userId);
        })->delete();
    
        $block = Block::create([
            'user_id' => $userId,
            'blocked_id' => $blockedId,
        ]);
    
        return response()->json(['message' => 'User blocked successfully'], 200);
    }
    

    public function unblockUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', 
        ]);
    
        Block::where('user_id', Auth::id())
             ->where('blocked_id', $request->user_id)
             ->delete();
    
        return response()->json(['message' => 'User unblocked successfully'], 200);
    }
    
}
