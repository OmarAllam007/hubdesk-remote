<?php

namespace App\Http\Middleware;

use Closure;

class ResetPassword
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user->password_reset || \Hash::check(env('DEFAULT_PASS'), $user->getAuthPassword())) {
            return redirect()->route('user.reset')->with('warning','warning');
        }
        return $next($request);
    }
}
