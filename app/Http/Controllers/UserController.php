<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FriendRequest;
use App\Models\Friendship;

class UserController extends Controller
{
    public function index()
    {
        $currentUserId = Auth::id();
        $students = User::where('role', 'user')->where('id', '!=', $currentUserId)->get();
        return view('view-students', ['students' => $students]);
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
