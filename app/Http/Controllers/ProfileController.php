<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
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
    
        $receivedFriendRequests = FriendRequest::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->get();
    
        $sentFriendRequests = FriendRequest::where('sender_id', $user->id)
            ->where('status', 'pending')
            ->get();
    
        $bookmarks = Bookmark::where('user_id', $user->id)->get();
        $savedComments = SavedComment::where('user_id', $user->id)->get();

        // Calculate the count of existing saved comments
        $existingCommentsCount = $savedComments->filter(function($savedComment) {
            return $savedComment->comment !== null; // Filter out saved comments that do not exist
        })->count();

        // Calculate the count of existing bookmarks
        $existingBookmarksCount = $bookmarks->filter(function($bookmark) {
            return $bookmark->post !== null; // Filter out bookmarks that do not exist
        })->count();

        $receivedMessages = Message::where('receiverId', $user->id)
        ->where('deleted', 0)
        ->orderBy('read')
        ->get();
    
        $unreadMessageCount = count($receivedMessages->where('read', 0));
        $sentMessages = Message::where('senderId', $user->id)->get();
        $joinedSocieties = Society::getSocietiesForUser($user->id);
        $friends = $user->friends;
    
        $ukUniversities = [
            'Sheffield Hallam University',
            'University of Sheffield',
        ];
    
        return view('profile', [
            'userId' => $userId,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'bio' => $user->bio,
            'university' => $user->university,
            'ukUniversities' => $ukUniversities,
            'bookmarks' => $bookmarks,
            'existingBookmarksCount' => $existingBookmarksCount,
            'comments' => $savedComments,
            'existingCommentsCount' => $existingCommentsCount,
            'joinedSocieties' => $joinedSocieties,
            'receivedFriendRequests' => $receivedFriendRequests,
            'sentFriendRequests' => $sentFriendRequests,
            'friends' => $friends,
            'receivedMessages' => $receivedMessages,
            'unreadMessageCount' => $unreadMessageCount,
            'sentMessages' => $sentMessages,
        ]);
    }
    
    public function updateProfile(Request $request)
    {
        $validatedData = $request->validate([
            'bio' => 'nullable|string',
            'university' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for avatar
        ]);
    
        $user = Auth::user();
    
        // Handle profile picture upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->move(public_path('avatars'), $request->file('avatar')->getClientOriginalName());
            $validatedData['avatar'] = 'avatars/' . $request->file('avatar')->getClientOriginalName();
        }
    
        $user->fill($validatedData)->save();
    
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function deleteMessage($id)
    {
        $message = Message::find($id);
    
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
    
        $message->delete();
    
        return response()->json(['message' => 'Message deleted successfully'], 200);
    }
}
