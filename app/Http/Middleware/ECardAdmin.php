<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ECardAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!can('e_card_admin')) {
            flash(t('Authorized'),t('You are not authorized to visit this area'),'danger');
            return \Redirect::to('/ticket');
        }

        return $next($request);
    }
}
