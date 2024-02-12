<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $ukUniversities = [
            'Sheffield Hallam University',
            'Unviersity of Sheffield',
        ];

        return view('profile', [
            'email' => $user->email,
            'avatar' => $user->avatar,
            'bio' => $user->bio,
            'university' => $user->university,
            'ukUniversities' => $ukUniversities,
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
