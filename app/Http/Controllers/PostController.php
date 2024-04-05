<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Society;
use App\Models\Post;
use App\Models\Bookmark;
use App\Models\Badge;
use App\Models\Report;

use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function viewPost($societyId, $postId)
    {
        $society = Society::find($societyId);
        $post = Post::find($postId);
    
        return view('view-post', compact('society', 'post'));
    }

    public function createPost(Request $request, $societyId)
    {
        $validatedData = $request->validate([
            'postTitle' => 'required',
            'postComment' => 'required',
        ]);

        $post = new Post();
        $post->authorId = auth()->user()->id;
        $post->societyId = $societyId;
        $post->postTitle = $validatedData['postTitle'];
        $post->postComment = $validatedData['postComment'];
        $post->pinned = false;
        $post->likes = 0;
        $post->dislikes = 0;
        $post->save();

        $existingBadge = Badge::where('user_id', auth()->user()->id)
                            ->where('badgeType', 'Made a Post')
                            ->exists();

        if (!$existingBadge) {
            $badge = new Badge([
                'user_id' => auth()->user()->id,
                'badgeType' => 'Made a Post',
            ]);
            $badge->save();
        }

        return redirect()->route('view-society', ['id' => $societyId])->with('success', 'Post created successfully!');
    }

    public function deletePost($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found.');
        }
    
        if ($post->authorId !== auth()->id() && !in_array(auth()->id(), $post->society->moderatorList)) {
            return redirect()->back()->with('error', 'You are not authorized to delete this post.');
        }
    
        $reports = Report::where('post_id', $postId)->get();
        foreach ($reports as $report) {
            $report->delete();
        }
    
        $post->delete();
    
        return redirect()->route('view-society', ['id' => $post->societyId])->with('success', 'Post deleted successfully.');
    }

    public function reportPost(Request $request, $postId)
    {
        $validatedData = $request->validate([
            'reportReasonPost' => 'required',
        ]);

        $post = Post::find($postId);
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found.');
        }

        $userId = auth()->id();

        $report = new Report([
            'user_id' => $userId,
            'post_id' => $postId,
            'society_id' => $request->input('societyId'),
            'reportType' => 'Post',
            'reportReason' => $request->input('reportReasonPost')
        ]);

        $report->save();

        return redirect()->back()->with('success', 'Post reported successfully.');
    }

    public function pinPost($postId)
    {
        $post = Post::find($postId);
    
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found.');
        }
    
        $post->pinned = !$post->pinned;
        $post->save();
    
        $action = $post->pinned ? 'pinned' : 'unpinned';
    
        return redirect()->back()->with('success', "Post $action successfully!");
    }

    public function likePost($postId)
    {
        $user = auth()->user();
        $post = Post::findOrFail($postId);
        $like = $user->likes()->where('post_id', $postId)->first();
    
        if ($like && $like->is_like) {
            $like->delete();
            if ($post->likes > 0) { 
                $post->decrement('likes'); 
            }
        } else {
            $user->likes()->updateOrCreate(['post_id' => $postId], ['is_like' => true]);
            $post->increment('likes'); 
        }
    
        return response()->json(['likes' => $post->likes]);
    }
    
    public function dislikePost($postId)
    {
        $user = auth()->user();
        $post = Post::findOrFail($postId);
        $like = $user->likes()->where('post_id', $postId)->first();
    
        if ($like && !$like->is_like) {
            $like->delete();
            if ($post->dislikes > 0) {
                $post->decrement('dislikes'); 
            }
        } else {
            $user->likes()->updateOrCreate(['post_id' => $postId], ['is_like' => false]);
            $post->increment('dislikes');
        }
    
        return response()->json(['dislikes' => max($post->dislikes, 0)]);
    }

    public function bookmarkPost(Request $request, $postId)
    {
        $user = auth()->user();
        $bookmark = Bookmark::where('user_id', $user->id)->where('post_id', $postId)->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['success' => 'Post unbookmarked'], 200);
        } else {
            $bookmark = new Bookmark();
            $bookmark->user_id = $user->id;
            $bookmark->post_id = $postId;
            $bookmark->save();
            return response()->json(['success' => 'Post bookmarked'], 200);
        }
    }

    public function unbookmarkPost($postId)
    {
        $user = auth()->user();
        $bookmark = Bookmark::where('user_id', $user->id)->where('post_id', $postId)->first();

        if ($bookmark) {
            $bookmark->delete();
            return redirect()->back()->with('success', 'Post unbookmarked successfully.');
        } else {
            return redirect()->back()->with('error', 'Bookmark not found.');
        }
    }

    public function checkBookmark($postId)
    {
        $user = auth()->user();
        $bookmark = Bookmark::where('user_id', $user->id)->where('post_id', $postId)->exists();

        return response()->json(['bookmarked' => $bookmark]);
    }    
}