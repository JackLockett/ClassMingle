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
    
        $friendRequests = FriendRequest::whereIn('sender_id', [$friendRequest->sender_id, $friendRequest->receiver_id])
            ->whereIn('receiver_id', [$friendRequest->sender_id, $friendRequest->receiver_id])
            ->get();
    
        foreach ($friendRequests as $request) {
            $request->delete();
        }
    
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
    
        Friendship::create([
            'user_id' => $friendRequest->sender_id,
            'friend_id' => $friendRequest->receiver_id,
            'status' => 'accepted',
        ]);
    
        Friendship::create([
            'user_id' => $friendRequest->receiver_id,
            'friend_id' => $friendRequest->sender_id,
            'status' => 'accepted',
        ]);
    
        return response()->json(['message' => 'Friend request accepted successfully']);
    }
    
    public function denyFriendRequest(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:friend_requests,id',
        ]);
    
        $friendRequest = FriendRequest::findOrFail($request->request_id);
        $friendRequest->delete();
    
        return response()->json([], 204);
    }
    

    public function deletePendingRequest(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:friend_requests,id',
        ]);
    
        $friendRequest = FriendRequest::findOrFail($request->request_id);
        $friendRequest->delete(); // Delete the friend request
    
        return response()->json([], 204);
    }
    
    
    public function removeFriend(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);
    
        $userId = auth()->id();
        $friendId = $request->friend_id;
    
        Friendship::where(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)
                ->where('friend_id', $friendId);
        })->orWhere(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $friendId)
                ->where('friend_id', $userId);
        })->delete();
    
        return response()->json([], 204); 
    }    
}
