<?php

namespace PMW\Http\Middleware;

use Closure;

class AuthAdminFakultas
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
        if($request->user()->isAdminFakultas())
            return $next($request);

        return redirect()->route('dashboard');
    }
}
