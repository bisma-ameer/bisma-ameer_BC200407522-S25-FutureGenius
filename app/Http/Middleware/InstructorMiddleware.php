<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsInstructor
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // adapt this to your role system; example assumes 'role' field or is_instructor boolean
        if (isset($user->role) && $user->role === 'instructor') {
            return $next($request);
        }

        if (isset($user->is_instructor) && $user->is_instructor) {
            return $next($request);
        }

        abort(403, 'Unauthorized - instructor only');
    }
}
