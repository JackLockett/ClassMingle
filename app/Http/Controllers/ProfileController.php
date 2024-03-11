<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark; 
use App\Models\SavedComment; 
use App\Models\Society;
use App\Models\FriendRequest;
use App\Models\Message;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $friendRequests = FriendRequest::where('receiver_id', $user->id)->where('status', 'pending')->get();
        $bookmarks = Bookmark::where('user_id', $user->id)->get();
        $savedComments = SavedComment::where('user_id', $user->id)->get();
        $messages = Message::where('receiverId', $user->id)->get();
        $joinedSocieties = Society::getSocietiesForUser($user->id);
        $friends = $user->friends;

        $ukUniversities = [
            'Sheffield Hallam University',
            'Unviersity of Sheffield',
        ];

        return view('profile', [
            'userId' => $userId,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'bio' => $user->bio,
            'university' => $user->university,
            'ukUniversities' => $ukUniversities,
            'bookmarks' => $bookmarks,
            'comments' => $savedComments,
            'joinedSocieties' => $joinedSocieties,
            'friendRequests' => $friendRequests,
            'friends' => $friends,
            'messages' => $messages,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validatedData = $request->validate([
            'bio' => 'nullable|string',
            'university' => 'nullable|string|max:255',
        ]);
    
        $user = Auth::user();
    
        $user->fill($validatedData)->save();
    
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

}
