<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Society;
use App\Models\Post;

class CommentController extends Controller
{
    public function viewComment($societyId, $postId, $commentId)
    {
        $society = Society::find($societyId);
        $post = Post::find($postId);
        $comment = Comment::find($commentId);
        
        return view('view-comment', compact('society', 'societyId', 'post', 'comment'));
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
        $responseComment->post_id = $parentComment->post_id; // Set the post_id
        $responseComment->parent_comment_id = $commentId; // Set the parent_comment_id
    
        $responseComment->save();
    
        return redirect()->route('view-comment', [
            'societyId' => $societyId,
            'postId' => $postId,
            'commentId' => $commentId
        ])->with('success', 'Response added successfully!');
        
    }
}
