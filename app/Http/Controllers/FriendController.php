<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FriendRequest;
use App\Models\Friendship;

class FriendController extends Controller
{
    public function sendFriendRequest(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $senderId = auth()->id();
        $receiverId = $request->receiver_id;

        if (FriendRequest::where('sender_id', $senderId)->where('receiver_id', $receiverId)->exists()) {
            return response()->json(['error' => 'Friend request already sent'], 422);
        }

        FriendRequest::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Friend request sent successfully']);
    }

    public function cancelFriendRequest(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $senderId = auth()->id();
        $receiverId = $request->receiver_id;

        $friendRequest = FriendRequest::where('sender_id', $senderId)
                                       ->where('receiver_id', $receiverId)
                                       ->first();

        if ($friendRequest) {
            $friendRequest->delete();
            return response()->json(['message' => 'Friend request canceled successfully']);
        } else {
            return response()->json(['error' => 'Friend request not found'], 404);
        }
    }

    public function acceptFriendRequest(Request $request)
    {
        $request->validate([
            'friend_request_id' => 'required|exists:friend_requests,id',
        ]);
    
        $friendRequest = FriendRequest::findOrFail($request->friend_request_id);
    
        // Check if the friendship already exists
        $existingFriendship = Friendship::where(function ($query) use ($friendRequest) {
            $query->where('user_id', $friendRequest->sender_id)
                  ->where('friend_id', $friendRequest->receiver_id);
        })->orWhere(function ($query) use ($friendRequest) {
            $query->where('user_id', $friendRequest->receiver_id)
                  ->where('friend_id', $friendRequest->sender_id);
        })->exists();
    
        if ($existingFriendship) {
            return response()->json(['error' => 'Friendship already exists'], 422);
        }
    
        // Create the friendship
        Friendship::create([
            'user_id' => $friendRequest->sender_id,
            'friend_id' => $friendRequest->receiver_id,
        ]);
    
        Friendship::create([
            'user_id' => $friendRequest->receiver_id,
            'friend_id' => $friendRequest->sender_id,
        ]);
    
        // Delete the corresponding friend request from the other person
        FriendRequest::where('sender_id', $friendRequest->receiver_id)
                     ->where('receiver_id', $friendRequest->sender_id)
                     ->delete();
    
        // Update the status of the friend request
        $friendRequest->status = 'accepted';
        $friendRequest->save();
    
        return response()->json(['message' => 'Friend request accepted successfully']);
    }
    
    public function denyFriendRequest(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:friend_requests,id',
        ]);
    
        $friendRequest = FriendRequest::findOrFail($request->request_id);
        $friendRequest->status = 'denied';
        $friendRequest->save();
    
        return response()->json(['message' => 'Friend request denied successfully']);
    }
    
    public function removeFriend(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);
    
        $userId = auth()->id();
        $friendId = $request->friend_id;
    
        Friendship::where('user_id', $userId)
                  ->where('friend_id', $friendId)
                  ->orWhere(function ($query) use ($userId, $friendId) {
                      $query->where('user_id', $friendId)
                            ->where('friend_id', $userId);
                  })
                  ->delete();
    
        return response()->json(['message' => 'Friend removed successfully']);
    }
}
