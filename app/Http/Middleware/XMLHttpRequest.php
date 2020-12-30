<?php

namespace PMW\Http\Middleware;

use Closure;

class XMLHttpRequest
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
        if($request->isXmlHttpRequest())
            return $next($request);

        return abort(404);
    }
}
