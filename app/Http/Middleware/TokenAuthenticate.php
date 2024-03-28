<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class TokenAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('token');

        if (!$token || !User::where('reset_token', $token)->exists()) {
            return redirect()->route('login')->withErrors(['token' => 'Invalid or expired token']);
        }

        return $next($request);
    }
}
