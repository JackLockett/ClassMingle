<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Society;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Bookmark;
use App\Models\SavedComment;
use App\Models\Query;

use Illuminate\Support\Facades\DB;

class SocietyController extends Controller
{
    public function index()
    {
        $academicSocieties = DB::table('societies')->where('societyType', 'Academic')->where('approved', 1)->paginate(6, ['*'], 'academic_page');
        $socialSocieties = DB::table('societies')->where('societyType', 'Social')->where('approved', 1)->paginate(6, ['*'], 'social_page');
    
        return view('societies', compact('academicSocieties', 'socialSocieties'));
    }
    

    public function viewSocietyInfo($id)
    {
        $society = Society::with(['posts' => function ($query) {
            $query->orderBy('pinned', 'desc')->orderByDesc('created_at')->withCount('comments');
        }])->findOrFail($id);
    
        return view('view-society', compact('society'));
    }
    
    public function createSociety(Request $request)
    {
        $validatedData = $request->validate([
            'societyType' => 'required',
            'societyName' => $request->societyType === 'academic' ? 'required' : '',
            'subjectList' => $request->societyType === 'academic' ? 'required' : '',
            'societyDescription' => 'required',
        ]);

        $society = new Society();

        $society->ownerId = auth()->user()->id;
        $society->societyType = $validatedData['societyType'];
        $society->societyName = $request->societyType === 'Academic' ? $validatedData['subjectList'] : $validatedData['societyName'];
        $society->societyDescription = $validatedData['societyDescription'];
        $society->approved = false;
        $society->memberList = [auth()->user()->id];
        $society->moderatorList = [auth()->user()->id];

        $society->save();

        $societyTypeName = $request->societyType === 'Academic' ? 'Academic' : 'Social';

        session()->flash('success', "Thank you for your submission! Your " . strtolower($societyTypeName) . " society will be reviewed by an administrator.");

        return redirect()->route('societies');
    }

    public function editSociety(Request $request, $societyId)
    {
        $society = Society::findOrFail($societyId);

        $validatedData = $request->validate([
            'societyDesc' => 'required',
        ]);

        $society->societyDescription = $validatedData['societyDesc'];
        $society->save();

        return redirect()->route('view-society', ['id' => $societyId])->with('success', 'Society details have been updated!');
    }

    public function claimSociety(Request $request, $societyId)
    {
        $society = Society::findOrFail($societyId);
        $user = auth()->user();

        $validatedData = $request->validate([
            'claimReason' => 'required',
        ]);
    
        $claimRequest = Query::create([
            'queryType' => 'Society Ownership Claim', 
            'user_id' => auth()->id(), 
            'username' => $user->username,
            'society_id' => $societyId,
            'societyName' => $society->societyName,
            'description' => $validatedData['claimReason'],
        ]);
    
        return redirect()->route('view-society', ['id' => $societyId])->with('success', 'Your ownership claim request has been submitted!');
    }
    
    public function deleteSociety($societyId)
    {
        $society = Society::findOrFail($societyId);
        $society->delete();
    
        return redirect()->route('societies')->with('success', 'The society has been deleted successfully.');
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
        $post->save();

        return redirect()->route('view-society', ['id' => $societyId])->with('success', 'Post created successfully!');
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
    

    public function deletePost($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found.');
        }

        if ($post->authorId !== auth()->id() && !in_array(auth()->id(), $post->society->moderatorList)) {
            return redirect()->back()->with('error', 'You are not authorized to delete this post.');
        }

        $post->delete();

        return redirect()->route('view-society', ['id' => $post->societyId])->with('success', 'Post deleted successfully.');
    }

    public function viewPost($societyId, $postId)
    {
        $society = Society::find($societyId);
        $post = Post::find($postId);
    
        return view('view-post', compact('society', 'post'));
    }

    public function bookmarkPost(Request $request, $postId)
    {
        $user = auth()->user();
        $bookmark = Bookmark::where('user_id', $user->id)->where('post_id', $postId)->first();

        if ($bookmark) {
            $bookmark->delete(); // Unbookmark if already bookmarked
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

    public function checkBookmark($postId)
    {
        $user = auth()->user();
        $bookmark = Bookmark::where('user_id', $user->id)->where('post_id', $postId)->exists();

        return response()->json(['bookmarked' => $bookmark]);
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
        $comment->save();

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
    
        // Check if the comment has responses
        if ($comment->responses()->count() > 0) {
            // Delete responses associated with the comment
            $comment->responses()->delete();
        }
    
        $comment->delete();
    
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
    
    public function promoteToModerator(Request $request, $societyId)
    {
        $society = Society::findOrFail($societyId);
        $selectedUserId = intval($request->input('moderatorUser'));
        $moderatorList = $society->moderatorList ?: [];
    
        if (!in_array($selectedUserId, $moderatorList)) {
            $moderatorList[] = $selectedUserId;
        }
    
        $society->update(['moderatorList' => $moderatorList]);
    
        return response()->json(['success' => true, 'message' => 'Moderator added successfully.', 'reload' => true]);
    }
    
    public function demoteModerator(Request $request, $societyId)
    {
        $society = Society::findOrFail($societyId);
        $selectedUserId = intval($request->input('demotedModerator'));
        $moderatorList = $society->moderatorList ?: [];
    
        $key = array_search($selectedUserId, $moderatorList);
        if ($key !== false) {
            unset($moderatorList[$key]);
            $society->update(['moderatorList' => array_values($moderatorList)]);
            return response()->json(['success' => true, 'message' => 'Moderator removed successfully.', 'reload' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Selected user is not a moderator.'], 400);
    }

    public function joinSociety($societyId)
    {
        $society = Society::find($societyId);
    
        if (!$society) {
            return response()->json(['error' => 'Society not found'], 404);
        }
    
        $memberList = $society->memberList ?: [];
        $userId = auth()->user()->id;
    
        if (!in_array($userId, $memberList)) {
            $memberList[] = $userId;
        }
    
        $society->update(['memberList' => $memberList]);
    
        return response()->json(['success' => 'User joined the society'], 200);
    }
    
    public function leaveSociety($societyId)
    {
        $society = Society::find($societyId);
    
        if (!$society) {
            return response()->json(['error' => 'Society not found'], 404);
        }
    
        $userId = auth()->user()->id;
    
        // Remove user from memberList
        $memberList = $society->memberList ?: [];
        $memberKey = array_search($userId, $memberList);
        if ($memberKey !== false) {
            unset($memberList[$memberKey]);
        }
    
        // Remove user from moderatorList
        $moderatorList = $society->moderatorList ?: [];
        $moderatorKey = array_search($userId, $moderatorList);
        if ($moderatorKey !== false) {
            unset($moderatorList[$moderatorKey]);
        }
    
        // Update society with updated memberList and moderatorList
        $society->update(['memberList' => array_values($memberList), 'moderatorList' => array_values($moderatorList)]);
    
        return response()->json(['success' => 'User left the society'], 200);
    }
    
}