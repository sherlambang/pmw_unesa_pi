<?php

namespace PMW\Http\Middleware;

use Closure;

class BolehUnggahProposal
{
    /**
     * Middleware ini akan mengecek apakah pengguna dapat
     * melakukan pengunggahan proposal
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param bool $final
     * @return mixed
     */
    public function handle($request, Closure $next, $final = false)
    {
        if(!$request->user()->isKetua() && !$request->user()->mahasiswa()->bisaUnggahProposal())
            return abort(404);

        return $next($request);
    }
    
}
