<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedComment;
use App\Models\Comment;
use App\Models\Society;
use App\Models\Badge;
use App\Models\Post;
use App\Models\Report;

class CommentController extends Controller
{
    public function viewComment($societyId, $postId, $commentId)
    {
        $society = Society::find($societyId);
        $post = Post::find($postId);
        $comment = Comment::find($commentId);
        
        return view('view-comment', compact('society', 'societyId', 'post', 'comment'));
    }

    public function saveComment(Request $request, $commentId)
    {
        $user = auth()->user();
        $comment = Comment::find($commentId);
    
        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
    
        $savedComment = SavedComment::where('user_id', $user->id)
                                     ->where('comment_id', $commentId)
                                     ->first();
    
        if ($savedComment) {
            $savedComment->delete();
            return response()->json(['success' => 'Comment unsaved'], 200);
        } else {
            $savedComment = new SavedComment();
            $savedComment->user_id = $user->id;
            $savedComment->comment_id = $commentId;
            $savedComment->save();
            return response()->json(['success' => 'Comment saved'], 200);
        }
    }

    public function unsaveComment($commentId)
    {
        $user = auth()->user();
        $savedComment = SavedComment::where('user_id', $user->id)->where('comment_id', $commentId)->first();

        if ($savedComment) {
            $savedComment->delete();
            return redirect()->back()->with('success', 'Comment unsaved successfully.');
        } else {
            return redirect()->back()->with('error', 'Saved comment not found.');
        }
    }

    public function reportComment(Request $request, $postId, $commentId)
    {
        $validatedData = $request->validate([
            'reportReasonComment' => 'required',
        ]);
    
        $comment = Comment::find($commentId);
        if (!$comment) {
            return redirect()->back()->with('error', 'Comment not found.');
        }
    
        $userId = auth()->id();
    
        $existingReport = Report::where('user_id', $userId)
                                 ->where('comment_id', $commentId)
                                 ->first();
    
        if ($existingReport) {
            return redirect()->back()->with('error', 'You have already reported this comment.');
        }
    
        $report = new Report([
            'user_id' => $userId,
            'post_id' => $postId,
            'comment_id' => $commentId,
            'society_id' => $request->input('societyId'),
            'reportType' => 'Comment',
            'reportReason' => $request->input('reportReasonComment')
        ]);
    
        $report->save();
    
        return redirect()->back()->with('success', 'Comment reported successfully.');
    }

    public function addComment(Request $request, $postId)
    {
        $validatedData = $request->validate([
            'comment' => 'required|string',
        ]);

        $post = Post::find($postId);

        if (!$post) {
            return abort(404, 'Post not found');
        }

        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->user_id = auth()->user()->id;
        $comment->comment = $validatedData['comment'];
        $comment->likes = 0;
        $comment->dislikes = 0;
        $comment->save();

        $existingBadge = Badge::where('user_id', auth()->user()->id)
                            ->where('badgeType', 'Made a Comment')
                            ->exists();

        if (!$existingBadge) {
            $badge = new Badge([
                'user_id' => auth()->user()->id,
                'badgeType' => 'Made a Comment',
            ]);
            $badge->save();
        }

        return redirect()->back()->with('success', 'Comment added successfully');
    }
    
    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);
    
        if (!$comment) {
            return redirect()->back()->with('error', 'Comment not found.');
        }
    
        $society = $comment->post->society;
        if (!in_array(auth()->id(), $society->moderatorList)) {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }
    
        $reports = Report::where('comment_id', $commentId)->get();
        foreach ($reports as $report) {
            $report->delete();
        }
    
        if ($comment->responses()->count() > 0) {
            $comment->responses()->delete();
        }
    
        $comment->delete();
    
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    public function respondToComment(Request $request, $societyId, $postId, $commentId)
    {
        $validatedData = $request->validate([
            'response' => 'required',
        ]);
    
        $parentComment = Comment::findOrFail($commentId);
    
        $responseComment = new Comment();
        $responseComment->user_id = auth()->user()->id;
        $responseComment->comment = $validatedData['response'];
        $responseComment->post_id = $parentComment->post_id; 
        $responseComment->parent_comment_id = $commentId;
    
        $responseComment->save();
    
        return redirect()->route('view-comment', [
            'societyId' => $societyId,
            'postId' => $postId,
            'commentId' => $commentId
        ])->with('success', 'Response added successfully!'); 
    }

    public function likeComment($commentId)
    {
        $user = auth()->user();
        $comment = Comment::findOrFail($commentId);
        $like = $user->likes()->where('comment_id', $commentId)->first();
    
        if ($like && $like->is_like) {
            $like->delete();
            if ($comment->likes > 0) { 
                $comment->decrement('likes'); 
            }
        } else {
            $user->likes()->updateOrCreate(['comment_id' => $commentId], ['is_like' => true]);
            $comment->increment('likes'); 
        }
    
        return response()->json(['likes' => $comment->likes]);
    }
    
    public function dislikeComment($commentId)
    {
        $user = auth()->user();
        $comment = Comment::findOrFail($commentId);
        $like = $user->likes()->where('comment_id', $commentId)->first();
    
        if ($like && !$like->is_like) {
            $like->delete();
            if ($comment->dislikes > 0) {
                $comment->decrement('dislikes'); 
            }
        } else {
            $user->likes()->updateOrCreate(['comment_id' => $commentId], ['is_like' => false]);
            $comment->increment('dislikes');
        }
    
        return response()->json(['dislikes' => max($comment->dislikes, 0)]);
    }
}
