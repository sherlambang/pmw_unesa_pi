<?php

namespace PMW\Http\Middleware;

use Closure;

class CekTimLengkap
{

    /**
     * Melakukan pengecekan apakah tim pengguna terkait sudah lengkap
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()->mahasisnya()->punyaTim() && $request->user()->mahasiswa()->timLengkap())
            return $next($request);

        return abort(404);
    }

}
