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

        // Adjust to your app's role implementation:
        // - if you use a 'role' column:
        if (isset($user->role) && $user->role === 'instructor') {
            return $next($request);
        }

        // - or if you use boolean flag:
        if (isset($user->is_instructor) && $user->is_instructor) {
            return $next($request);
        }

        // - or use Spatie's package:
        // if (method_exists($user, 'hasRole') && $user->hasRole('instructor')) { return $next($request); }

        abort(403, 'Unauthorized - instructor only');
    }
}
