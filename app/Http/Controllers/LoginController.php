<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/discovery');
        }

        return back()->withErrors(['invalidCredentials' => 'Invalid email or password.']);
    }

    protected function loggedOut(Request $request)
    {
        // Add any additional functionality you want after logout here
        return redirect('/'); // Redirect to a different page after logout if desired
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }

}
