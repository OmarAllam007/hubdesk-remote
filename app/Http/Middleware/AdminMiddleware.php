<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->isAdmin()) {
            flash(t('Authorized'),t('You are not authorized to visit this area'),'danger');
            return \Redirect::to('/home');
        }

        return $next($request);
    }
}
