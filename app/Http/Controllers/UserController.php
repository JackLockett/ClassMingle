<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FriendRequest;
use App\Models\Friendship;
use App\Models\Message;

class UserController extends Controller
{
    public function index()
    {
        $currentUserId = Auth::id();
        $students = User::where('role', 'user')->where('id', '!=', $currentUserId)->get();
        return view('view-students', ['students' => $students]);
    }    

    public function sendMessage(Request $request, $id)
    {
        $currentUserId = Auth::id();
    
        $message = new Message([
            'senderId' => $currentUserId,
            'receiverId' => $id,
            'message' => $request->input('messageField'),
        ]);
    
        $message->save();
    
        return redirect()->route('user.profile', ['id' => $id])->with('success', 'Message sent successfully.');
    }

    public function deleteMessage($id)
    {
        // Find the message by its ID and delete it
        $message = Message::findOrFail($id);
        $message->delete();
    
        // Redirect to the appropriate route or return a response
        return redirect()->back()->with('success', 'Message deleted successfully.');
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

        return view('student', [
            'student' => $student,
            'isPendingRequest' => $isPendingRequest,
            'isFriend' => $isFriend,
            'authId' => $authId,
        ]);
    }
}
