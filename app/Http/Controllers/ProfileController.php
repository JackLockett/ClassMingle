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

        $existingCommentsCount = $savedComments->filter(function($savedComment) {
            return $savedComment->comment !== null;
        })->count();

        $existingBookmarksCount = $bookmarks->filter(function($bookmark) {
            return $bookmark->post !== null; 
        })->count();

        $receivedMessages = Message::where('receiverId', $user->id)
        ->where('deleted', 0)
        ->orderBy('read')
        ->get();
    
        $unreadMessageCount = count($receivedMessages->where('read', 0));
        $sentMessages = Message::where('senderId', $user->id)->get();
        $joinedSocieties = Society::getSocietiesForUser($user->id);
        $friends = $user->friends;
    
        $filePath = public_path('universities.json');
        $jsonContents = file_get_contents($filePath);
        $decodedJson = json_decode($jsonContents, true);
    
        return view('profile', [
            'userId' => $userId,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'bio' => $user->bio,
            'university' => $user->university,
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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = Auth::user();
    
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
