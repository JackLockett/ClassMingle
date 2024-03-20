<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Society;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
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

        // Remove the user from societies where they are a member
        $societies = Society::whereJsonContains('memberList', $user->id)->get();
        foreach ($societies as $society) {
            $memberList = $society->memberList;
            $index = array_search($user->id, $memberList);
            if ($index !== false) {
                unset($memberList[$index]);
                $society->update(['memberList' => $memberList]);
            }
        }

        // Remove the user from societies where they are a moderator
        $societies = Society::whereJsonContains('moderatorList', $user->id)->get();
        foreach ($societies as $society) {
            $moderatorList = $society->moderatorList;
            $index = array_search($user->id, $moderatorList);
            if ($index !== false) {
                unset($moderatorList[$index]);
                $society->update(['moderatorList' => $moderatorList]);
            }
        }
    
        $user->delete();
    
        return redirect()->route('admin-panel')->with('success', 'User deleted successfully!');
    }
}
