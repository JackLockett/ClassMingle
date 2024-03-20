<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Society;
use App\Models\Message;
use App\Models\Post;
use App\Models\Comment;
use App\Models\FriendRequest;
use App\Models\Friendship;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
        ->where('username', '!=', 'admin')
        ->get();

        $societies = Society::where('approved', 1)->get();
        $pendingSocieties = Society::where('approved', 0)->get();
        
        $ukUniversities = [
            'Sheffield Hallam University',
            'University of Sheffield',
        ];

        $societyTypes = [
            'Academic',
            'Social'
        ];

        return view('admin-panel', [
            'users' => $users,
            'societies' => $societies,
            'societyTypes' => $societyTypes,
            'ukUniversities' => $ukUniversities,
            'pendingSocieties' => $pendingSocieties,
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bio' => 'nullable|string|max:250',
            'university' => 'nullable|string|max:45',
            'role' => 'nullable|string|max:45',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
    
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        if ($request->has('bio')) {
            $user->bio = $request->bio;
        }
    
        if ($request->has('university')) {
            $user->university = $request->university;
        }
    
        if ($request->has('role')) {
            $user->role = $request->role;
        }
    
        $user->save();

        return redirect()->route('admin-panel')->with('success', 'User details updated successfully!');
    }


    public function updateSociety(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string|max:250',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
    
        $society = Society::find($id);
    
        if (!$society) {
            return response()->json(['error' => 'Society not found'], 404);
        }
    
        if ($request->has('description')) {
            $society->societyDescription = $request->description;
        }
    
        $society->save();
    
        return redirect()->route('admin-panel')->with('success', 'Society details updated successfully!');
    }

    public function acceptSociety(Request $request, $id)
    {
        $society = Society::find($id);

        if (!$society) {
            return response()->json(['error' => 'Society not found'], 404);
        }

        $society->approved = true;
        $society->save();

        return redirect()->route('admin-panel')->with('success', 'Society approved successfully!');
    }

    public function denySociety(Request $request, $id)
    {
        $society = Society::find($id);

        if (!$society) {
            return response()->json(['error' => 'Society not found'], 404);
        }

        $society->approved = -1;
        $society->save();

        return redirect()->route('admin-panel')->with('success', 'Society denied successfully!');
    }

    public function deleteSociety(Request $request, $id)
    {
        $society = Society::find($id);
    
        if (!$society) {
            return response()->json(['error' => 'Society not found'], 404);
        }
    
        $society->delete();
    
        return redirect()->route('admin-panel')->with('success', 'Society deleted successfully!');
    }
    
    public function deleteUser(Request $request, $id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        Society::whereIn('id', function ($query) use ($user) {
            $query->select('id')
                ->from('societies')
                ->whereJsonContains('memberList', $user->id)
                ->orWhereJsonContains('moderatorList', $user->id);
        })->each(function ($society) use ($user) {
            $society->update([
                'memberList' => array_diff($society->memberList, [$user->id]),
                'moderatorList' => array_diff($society->moderatorList, [$user->id]),
            ]);
        });
    
        Society::where('ownerId', $user->id)->delete();
        Post::where('authorId', $user->id)->delete();
        Comment::where('user_id', $user->id)->delete();
        Message::where('senderId', $user->id)->orWhere('receiverId', $user->id)->delete();
        FriendRequest::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->delete();
        Friendship::where('user_id', $user->id)->orWhere('friend_id', $user->id)->delete();

        $user->delete();
    
        return redirect()->route('admin-panel')->with('success', 'User deleted successfully!');
    }
}
