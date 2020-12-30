<?php

namespace PMW\Http\Middleware;

use Closure;

class AuthMahasiswa
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
        if($request->user()->isMahasiswa())
            return $next($request);

        return redirect()->route('dashboard');
    }
}
