<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Ban;
use Carbon\Carbon;

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
            $user = Auth::user();
            $ban = Ban::where('user_id', $user->id)->first();
    
            if ($ban && Carbon::now() < Carbon::parse($ban->created_at)->addDays($ban->banDuration)) {
                $banExpirationDate = Carbon::parse($ban->created_at)->addDays($ban->banDuration)->format('jS F Y');
                $banReason = $ban->banReason;
    
                Auth::logout();
                return back()->withErrors(['banned' => 'Your account is currently banned. You will be unbanned on: ' . $banExpirationDate . '. Reason: ' . $banReason]);
            }
    
            if ($user->verified == 0) {
                Auth::logout();
                return back()->withErrors(['notVerified' => 'Your account is not verified. Please check your email for verification instructions.']);
            }
    
            return redirect()->intended('/discovery');
        }
    
        return back()->withErrors(['invalidCredentials' => 'Invalid email or password.']);
    }    

    protected function loggedOut(Request $request)
    {
        return redirect('/'); 
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }

}
