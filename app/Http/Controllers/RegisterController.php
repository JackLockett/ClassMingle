<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'username' => 'required|min:4|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = new User([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'role' => 'user',
        ]);

        $user->save();

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    public function checkUsernameAvailability($username)
    {
        $isAvailable = !User::where('username', $username)->exists();

        return response()->json(['isAvailable' => $isAvailable]);
    }
}
