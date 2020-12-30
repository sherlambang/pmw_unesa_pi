<?php

namespace PMW\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthDosenPembimbing
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() && Auth::user()->isDosenPembimbing())
            return $next($request);

        return redirect()->route('dashboard');
    }

}
