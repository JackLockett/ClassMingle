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
use App\Models\Query;
use App\Models\Badge;
use App\Models\Report;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
        ->where('username', '!=', 'admin')
        ->get();

        $societies = Society::where('approved', 1)->get();
        $pendingSocieties = Society::where('approved', 0)->get();
        $queries = Query::all();
        $reports = Report::all();
        
        $ukUniversities = [
            'Sheffield Hallam University',
            'University of Sheffield',
        ];

        $societyTypes = [
            'Academic',
            'Social'
        ];

        $queryTypes = [
            'Society Ownership Claim',
        ];

        $typesOfReports = [
            'Post',
            'Comment'
        ];

        return view('admin-panel', [
            'users' => $users,
            'societies' => $societies,
            'societyTypes' => $societyTypes,
            'ukUniversities' => $ukUniversities,
            'pendingSocieties' => $pendingSocieties,
            'queryTypes' => $queryTypes,
            'queries' => $queries,
            'typesOfReports' => $typesOfReports,
            'reports' => $reports,
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
        $societyOwner = $society->ownerId;

        if (!$society) {
            return response()->json(['error' => 'Society not found'], 404);
        }

        $society->approved = true;
        $society->save();

        // Check if there's already a row with the given user_id and badgeType
        $existingBadge = Badge::where('user_id', $societyOwner)
                            ->where('badgeType', 'Created a Society')
                            ->exists();

        if (!$existingBadge) {
            // Give the user a "Created a Society" badge
            $badge = new Badge([
                'user_id' => $societyOwner,
                'badgeType' => 'Created a Society',
            ]);
            $badge->save();
        }

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

    public function acceptSocietyClaim(Request $request, $societyId)
    {
        $society = Society::findOrFail($societyId);
    
        $validatedData = $request->validate([
            'claimerId' => 'required', 
        ]);
    
        $existingRequests = Query::where('society_id', $societyId)
                                ->where('queryType', 'Society Ownership Claim')
                                ->get();
    
        foreach ($existingRequests as $existingRequest) {
            $existingRequest->delete();
        }
    
        $society->ownerId = $validatedData['claimerId'];
    
        $moderatorList = $society->moderatorList ?: [];
        $claimerId = intval($validatedData['claimerId']);
        if (!in_array($claimerId, $moderatorList)) {
            $moderatorList[] = $claimerId;
        }
        $society->moderatorList = $moderatorList;
    
        $society->save();
    
        return redirect()->route('admin-panel')->with('success', 'The ownership of the society has been transferred successfully!');
    }

    public function denySocietyClaim(Request $request, $id)
    {
        $query = Query::find($id);
    
        if (!$query) {
            return redirect()->route('admin-panel')->with('error', 'Query not found!');
        }
    
        $query->delete();
    
        return redirect()->route('admin-panel')->with('success', 'Query denied successfully!');
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
    
        // Retrieve societies associated with the user
        $societies = Society::whereIn('id', function ($query) use ($user) {
            $query->select('id')
                ->from('societies')
                ->whereJsonContains('memberList', $user->id)
                ->orWhereJsonContains('moderatorList', $user->id);
        })->get();
    
        // Iterate over each society and update ownerId if necessary
        $societies->each(function ($society) use ($user) {
            // Update ownerId if the user is the current owner
            if ($society->ownerId == $user->id) {
                // Remove the user from the moderatorList
                $moderators = array_values(array_diff($society->moderatorList, [$user->id]));
                $society->update(['moderatorList' => $moderators]);
    
                // Update ownerId based on remaining moderators
                $society->update(['ownerId' => empty($moderators) ? -1 : $moderators[0]]);
            }
    
            // Remove the user from the moderatorList and memberList
            $society->update([
                'moderatorList' => array_values(array_diff($society->moderatorList, [$user->id])),
                'memberList' => array_values(array_diff($society->memberList, [$user->id]))
            ]);
        });
    
        // Delete other related records
        FriendRequest::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->delete();
        Friendship::where('user_id', $user->id)->orWhere('friend_id', $user->id)->delete();
    
        // Finally, delete the user
        $user->delete();
    
        return redirect()->route('admin-panel')->with('success', 'User deleted successfully!');
    }
}
