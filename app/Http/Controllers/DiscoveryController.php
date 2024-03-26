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
    
        $popularSocieties = Society::where('approved', 1)
            ->orderByDesc('memberList')
            ->take(10)
            ->get();

        $personalFeedPosts = Post::whereHas('society', function ($query) use ($userId) {
            $query->whereJsonContains('memberList', $userId);
        })
        ->where('authorId', '!=', $userId)
        ->latest()
        ->take(10)
        ->get();

        $personalFeedPosts = $personalFeedPosts->shuffle();

        return view('discovery', compact('popularSocieties', 'personalFeedPosts'));
    }
}
