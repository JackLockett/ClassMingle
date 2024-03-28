<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class PasswordResetController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('forgot-password');
    }

    public function showNewPasswordForm(Request $request)
    {
        $token = $request->query('token');
        return view('reset-password', compact('token'));
    }

    public function sendPasswordResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email address not found.'])->withInput();
        }
    
        $token = Str::random(60);
    
        $user->update(['reset_token' => $token]);
    
        Mail::to($user->email)->send(new ResetPasswordMail($user, $token));
    
        return redirect()->back()->with('success', 'Password reset link sent to your email.');
    }

    public function showResetPasswordForm($token)
    {
        $user = User::where('reset_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['token' => 'Invalid token.']);
        }

        return view('reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min' => 'The password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('reset_token', $request->token)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['token' => 'Invalid token.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
        ]);

        return redirect()->route('login')->with('success', 'Password reset successful. Please login with your new password.');
    }
}
