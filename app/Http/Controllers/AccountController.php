<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Society;
use App\Models\Message;
use App\Models\Post;
use App\Models\Comment;
use App\Models\FriendRequest;
use App\Models\Friendship;
use App\Models\User;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $email = $user->email;
        $created_at = $user->created_at->format('jS F Y');
        return view('account', compact('email', 'created_at'));
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        
        Society::whereIn('id', function ($query) use ($user) {
            $query->select('id')
                ->from('societies')
                ->whereJsonContains('memberList', $user->id)
                ->orWhereJsonContains('moderatorList', $user->id);
        })->each(function ($society) use ($user) {
            $society->update([
                'memberList' => array_diff($society->memberList, [$user->id]),
                'moderatorList' => array_diff($society->moderatorList, [$user->id]),
            ]);
        });
    
        Society::where('ownerId', $user->id)->delete();
        Post::where('authorId', $user->id)->delete();
        Comment::where('user_id', $user->id)->delete();
        Message::where('senderId', $user->id)->orWhere('receiverId', $user->id)->delete();
        FriendRequest::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->delete();
        Friendship::where('user_id', $user->id)->orWhere('friend_id', $user->id)->delete();

        $user->delete();
    
        return redirect()->route('login')->with('success', 'Your account has been deleted successfully.');
    }
    
    
    public function changeEmail(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'newEmail' => ['required', 'email', 'unique:users,email'],
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The provided password does not match our records.');
                }
            }],
        ], [
            'newEmail.unique' => 'The new email address is already in use.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account')
                ->withErrors(['change_email' => $validator->errors()->first()])
                ->withInput();
        }

        if ($user->email !== $request->input('newEmail')) {
            $user->email = $request->input('newEmail');
            $user->save();
            return redirect()->route('account')->with('success', 'Email changed successfully');
        }

        return redirect()->route('account')->with('info', 'No changes made to your email.');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The provided current password is incorrect.');
                }
            }],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ], [
            'new_password.min' => 'The new password must be at least 8 characters.',
            'new_password.confirmed' => 'The new password and confirmation do not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account')
                ->withErrors(['change_password' => $validator->errors()->first()])
                ->withInput();
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect()->route('account')->with('success', 'Password changed successfully');
    }
}
