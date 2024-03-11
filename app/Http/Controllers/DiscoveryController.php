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
    
        $suggestedSocieties = $allSocieties->filter(function ($society) use ($userId) {
            if (is_array($society->memberList)) {
                $members = $society->memberList;
            } else {
                $members = json_decode($society->memberList, true);
            }

            return !in_array($userId, $members);
        })->shuffle()->take(10);

        $personalFeedPosts = Post::whereHas('society', function ($query) use ($userId) {
            $query->whereJsonContains('memberList', $userId);
        })
        ->where('authorId', '!=', $userId)
        ->latest()
        ->take(10)
        ->get();

        $personalFeedPosts = $personalFeedPosts->shuffle();

        return view('discovery', compact('suggestedSocieties', 'personalFeedPosts'));
    }
}
