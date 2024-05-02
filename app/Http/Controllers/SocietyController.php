<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Society;
use App\Models\Badge;

use Illuminate\Support\Facades\DB;

class SocietyController extends Controller
{
    public function index()
    {
        $academicSocieties = DB::table('societies')->where('societyType', 'Academic')->where('approved', 1)->paginate(6, ['*'], 'academic_page');
        $socialSocieties = DB::table('societies')->where('societyType', 'Social')->where('approved', 1)->paginate(6, ['*'], 'social_page');
    
        $filePath = public_path('subjects.json');
        $jsonContents = file_get_contents($filePath);
        $subjects = json_decode($jsonContents, true)['subjects'];
    
        $allSocieties = array_merge($academicSocieties->items(), $socialSocieties->items());
    
        // Remove subjects that already have a corresponding society
        foreach ($allSocieties as $society) {
            $index = array_search($society->societyName, $subjects);
            if ($index !== false) {
                unset($subjects[$index]);
            }
        }
    
        return view('societies', compact('academicSocieties', 'socialSocieties', 'subjects'));
    }
    
    public function viewSocietyInfo($id)
    {
        $society = Society::with(['posts' => function ($query) {
            $query->orderBy('pinned', 'desc')->orderByDesc('created_at')->withCount('comments');
        }])->findOrFail($id);
    
        return view('view-society', compact('society'));
    }
    
    public function createSociety(Request $request)
    {
        $validatedData = $request->validate([
            'societyType' => 'required',
            'subjectList' => $request->societyType === 'Academic' ? 'required' : '',
            'academicSocietyDescription' => $request->societyType === 'Academic' ? 'required' : '',
            'socialSocietyDescription' => $request->societyType === 'Social' ? 'required' : '',
        ]);
    
        if ($request->societyType === 'Academic') {
            $societyName = $validatedData['subjectList']; // Set society name to subject name for academic societies
        } else {
            $societyName = $request->input('societyName');
        }
    
        $existingSociety = Society::where('societyName', $societyName)->first();
    
        if ($existingSociety) {
            return redirect()->back()->withInput()->withErrors(['societyName' => 'A society with this name already exists.']);
        }
    
        $society = new Society();
    
        $society->ownerId = auth()->user()->id;
        $society->societyType = $validatedData['societyType'];
        $society->societyName = $societyName;
        $society->societyDescription = $request->societyType === 'Academic' ? $validatedData['academicSocietyDescription'] : $validatedData['socialSocietyDescription'];
        $society->approved = false;
        $society->memberList = [auth()->user()->id];
        $society->moderatorList = [auth()->user()->id];
    
        $society->save();
    
        $societyTypeName = $request->societyType === 'Academic' ? 'Academic' : 'Social';
    
        session()->flash('success', "Thank you for your submission! Your " . strtolower($societyTypeName) . " society will be reviewed by an administrator.");
    
        return redirect()->route('societies');
    }
    

    public function joinSociety($societyId)
    {
        $society = Society::find($societyId);
    
        if (!$society) {
            return response()->json(['error' => 'Society not found'], 404);
        }
    
        $memberList = $society->memberList ?: [];
        $userId = auth()->user()->id;
    
        if (!in_array($userId, $memberList)) {
            $memberList[] = $userId;
        }
    
        $society->update(['memberList' => $memberList]);

        $existingBadge = Badge::where('user_id', $userId)
                            ->where('badgeType', 'Joined a Society')
                            ->exists();

        if (!$existingBadge) {
            $badge = new Badge([
                'user_id' => $userId,
                'badgeType' => 'Joined a Society',
            ]);
            $badge->save();
        }
    
        return response()->json(['success' => 'User joined the society'], 200);
    }

    public function leaveSociety($societyId)
    {
        $society = Society::find($societyId);
    
        if (!$society) {
            return response()->json(['error' => 'Society not found'], 404);
        }
    
        $userId = auth()->user()->id;
    
        $memberList = $society->memberList ?: [];
        $memberKey = array_search($userId, $memberList);
        if ($memberKey !== false) {
            unset($memberList[$memberKey]);
        }
    
        $moderatorList = $society->moderatorList ?: [];
        $moderatorKey = array_search($userId, $moderatorList);
        if ($moderatorKey !== false) {
            unset($moderatorList[$moderatorKey]);
        }
    
        $society->update(['memberList' => array_values($memberList), 'moderatorList' => array_values($moderatorList)]);
    
        return response()->json(['success' => 'User left the society'], 200);
    }

    public function editSociety(Request $request, $societyId)
    {
        $society = Society::findOrFail($societyId);

        $validatedData = $request->validate([
            'societyDesc' => 'required',
        ]);

        $society->societyDescription = $validatedData['societyDesc'];
        $society->save();

        return redirect()->route('view-society', ['id' => $societyId])->with('success', 'Society details have been updated!');
    }

    public function claimSociety(Request $request, $societyId)
    {
        $society = Society::findOrFail($societyId);
        $user = auth()->user();

        $validatedData = $request->validate([
            'claimReason' => 'required',
        ]);
    
        $claimRequest = Query::create([
            'queryType' => 'Society Ownership Claim', 
            'user_id' => auth()->id(), 
            'username' => $user->username,
            'society_id' => $societyId,
            'societyName' => $society->societyName,
            'description' => $validatedData['claimReason'],
        ]);
    
        return redirect()->route('view-society', ['id' => $societyId])->with('success', 'Your ownership claim request has been submitted!');
    }
    
    public function deleteSociety($societyId)
    {
        $society = Society::findOrFail($societyId);
        $society->delete();
    
        return redirect()->route('societies')->with('success', 'The society has been deleted successfully.');
    }
    
    public function promoteToModerator(Request $request, $societyId)
    {
        $society = Society::findOrFail($societyId);
        $selectedUserId = intval($request->input('moderatorUser'));
        $moderatorList = $society->moderatorList ?: [];
    
        if (!in_array($selectedUserId, $moderatorList)) {
            $moderatorList[] = $selectedUserId;
        }
    
        $society->update(['moderatorList' => $moderatorList]);
    
        return response()->json(['success' => true, 'message' => 'Moderator added successfully.', 'reload' => true]);
    }
    
    public function demoteModerator(Request $request, $societyId)
    {
        $society = Society::findOrFail($societyId);
        $selectedUserId = intval($request->input('demotedModerator'));
        $moderatorList = $society->moderatorList ?: [];
    
        $key = array_search($selectedUserId, $moderatorList);
        if ($key !== false) {
            unset($moderatorList[$key]);
            $society->update(['moderatorList' => array_values($moderatorList)]);
            return response()->json(['success' => true, 'message' => 'Moderator removed successfully.', 'reload' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Selected user is not a moderator.'], 400);
    }
}