<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Society;
use App\Models\Post;

class DiscoveryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $allSocieties = Society::all();
    
        // Filter out societies where the user is already a member
        $suggestedSocieties = $allSocieties->filter(function ($society) use ($userId) {
            $members = json_decode($society->memberList, true);
            return !in_array($userId, $members);
        })->shuffle()->take(10); // Shuffle the filtered societies and take 10 random ones

        // Fetch posts from societies where the current user is a member
        $personalFeedPosts = Post::whereHas('society', function ($query) use ($userId) {
            $query->whereJsonContains('memberList', $userId);
        })
        ->where('authorId', '!=', $userId) // Exclude posts authored by the current user
        ->latest()
        ->take(10)
        ->get();
        
        

        return view('discovery', compact('suggestedSocieties', 'personalFeedPosts'));
    }
}
