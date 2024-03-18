<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        
        $ukUniversities = [
            'Sheffield Hallam University',
            'University of Sheffield',
        ];

        return view('admin-panel', ['users' => $users, 'ukUniversities' => $ukUniversities]);
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
}
