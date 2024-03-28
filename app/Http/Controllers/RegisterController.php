<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyAccountMail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $filePath = public_path('universities.json');
        $jsonContents = file_get_contents($filePath);
        $decodedJson = json_decode($jsonContents, true);
        
        $ukUniversities = [];
        foreach ($decodedJson as $university) {
            $ukUniversities[] = $university['name'];
        }
    
        return view('register', ['ukUniversities' => $ukUniversities]);
    }

    public function showVerificationForm()
    {
        return view('resend-verification-email');
    }

    public function register(Request $request)
    {
        $emailDomain = substr(strrchr($request->input('email'), "@"), 1);

        $selectedUniversity = $request->input('university');
        $universityDomain = $this->getUniversityDomains($selectedUniversity);

        $this->validate($request, [
            'email' => 'required|email|unique:users|allowed_email_domain',
            'username' => 'required|min:4|unique:users',
            'university' => 'required|not_in:""',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'Email already exists.',
            'username.required' => 'Username is required.',
            'username.min' => 'Username must be at least :min characters.',
            'username.unique' => 'Username already exists.',
            'university.required' => 'University is required.',
            'university.not_in' => 'Please select a valid university.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least :min characters.',
        ]);
        
        $user = new User([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'avatar' => 'images/default.jpg',
            'university' => $request->input('university'),
            'role' => 'user',
            'verified' => false
        ]);

        $user->save();

        $existingBadge = Badge::where('user_id', $user->id)
                            ->where('badgeType', 'New User')
                            ->exists();

        if (!$existingBadge) {
            $badge = new Badge([
                'user_id' => $user->id,
                'badgeType' => 'New User',
            ]);
            $badge->save();
        }

        $token = Str::random(60);
    
        $user->update(['verify_token' => $token]);
        Mail::to($user->email)->send(new VerifyAccountMail($user, $token));

        return redirect()->route('login')->with('success', 'Registration successful! Check your email to verify your account!');
    }

    protected function getUniversities()
    {
        $filePath = public_path('universities.json');
        $jsonContents = file_get_contents($filePath);
        $decodedJson = json_decode($jsonContents, true);
    
        return $decodedJson ?? [];
    }    
    
    protected function getUniversityDomains($selectedUniversity)
    {
        $filePath = public_path('universities.json');
        $jsonContents = file_get_contents($filePath);
        $decodedJson = json_decode($jsonContents, true);
    
        foreach ($decodedJson as $university) {
            if ($university['name'] === $selectedUniversity) {
                return $university['domains'];
            }
        }
    
        return [];
    }      
    
    public function checkUsernameAvailability($username)
    {
        $isAvailable = !User::where('username', $username)->exists();

        return response()->json(['isAvailable' => $isAvailable]);
    }

    public function allowedEmailDomain($attribute, $value, $parameters, $validator)
    {
        $emailDomain = substr(strrchr($value, "@"), 1);
        $selectedUniversity = $validator->getData()['university']; 
        $universityDomains = $this->getUniversityDomains($selectedUniversity);
    
        return in_array($emailDomain, $universityDomains);
    }

    public function verifyAccount(Request $request)
    {
        $token = $request->token;
        
        $user = User::where('verify_token', $token)->first();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid token or account already verified.');
        }
    
        $user->verified = true;
        $user->verify_token = null;
        $user->save();
        
        return redirect()->route('login')->with('success', 'Your account has been verified! You can now login.');
    } 
    
    public function resendVerification(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User with this email address does not exist.']);
        }        

        if ($user->verified) {
            return back()->withErrors(['email' => 'Your account is already verified.']);
        }

        $token = Str::random(60);

        $user->update(['verify_token' => $token]);

        Mail::to($user->email)->send(new VerifyAccountMail($user, $token));

        return back()->with('success', 'Verification email resent successfully.');
    }
}
