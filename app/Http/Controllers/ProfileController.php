<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark; 

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookmarks = Bookmark::where('user_id', $user->id)->get();

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
            'bookmarks' => $bookmarks, // Pass bookmarks to the view
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
