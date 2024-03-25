<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Badge;

class RegisterController extends Controller
{
    // Path to the JSON file containing allowed email domains
    protected $allowedEmailDomainsFilePath = 'allowed_email_domains.json';

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        // Load allowed email domains from JSON file
        $allowedEmailDomains = $this->getAllowedEmailDomains();

        // Add custom validation rule for email domain
        $this->validate($request, [
            'email' => 'required|email|unique:users|allowed_email_domain',
            'username' => 'required|min:4|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = new User([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'avatar' => 'images/default.jpg',
            'role' => 'user',
        ]);

        $user->save();

        // Check if there's already a row with the given user_id and badgeType
        $existingBadge = Badge::where('user_id', $user->id)
                            ->where('badgeType', 'New User')
                            ->exists();

        if (!$existingBadge) {
            // Give the user a "New User" badge
            $badge = new Badge([
                'user_id' => $user->id,
                'badgeType' => 'New User',
            ]);
            $badge->save();
        }

        return redirect()->route('login')->with('success', 'Registration successful! You can now login!');
    }

    public function checkUsernameAvailability($username)
    {
        $isAvailable = !User::where('username', $username)->exists();

        return response()->json(['isAvailable' => $isAvailable]);
    }

    // Read allowed email domains from JSON file
    protected function getAllowedEmailDomains()
    {
        $fileContent = File::get(public_path($this->allowedEmailDomainsFilePath));
        $jsonData = json_decode($fileContent, true);

        return $jsonData['domains'] ?? [];
    }

    // Custom validation rule for allowed email domain
    public function allowedEmailDomain($attribute, $value, $parameters, $validator)
    {
        $domain = substr(strrchr($value, "@"), 1);
        $allowedDomains = $this->getAllowedEmailDomains();

        return in_array($domain, $allowedDomains);
    }
}
