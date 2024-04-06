<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Block;

class CheckBlockedUser
{
    public function handle($request, Closure $next)
    {
        $profileOwnerId = $request->route()->parameter('id');
        $authenticatedUserId = auth()->id();

        // Check if the authenticated user is blocked by the profile owner
        if (Block::where('user_id', $profileOwnerId)->where('blocked_id', $authenticatedUserId)->exists()) {
            return redirect()->route('view-students')->with('error', 'You are blocked by this user.');
        }

        return $next($request);
    }
}
