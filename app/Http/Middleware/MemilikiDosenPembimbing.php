<?php

namespace PMW\Http\Middleware;

use Closure;

class MemilikiDosenPembimbing
{
    /**
     * Mengecek apakah pengguna terkait sudah memiliki dosen pembimbing
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()->mahasiswa()->proposal()->punyaPembimbing())
            return $next($request);

        return abort(404);
    }
    
}
