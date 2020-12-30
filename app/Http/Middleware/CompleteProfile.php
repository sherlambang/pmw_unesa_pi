<?php

namespace PMW\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CompleteProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ((($request->user()->isMahasiswa() ||
                    $request->user()->isDosenPembimbing()) &&
                is_null($request->user()->id_prodi)) ||
            ($request->user()->isReviewer() &&
                is_null($request->user()->nama))) {
            Session::flash('message', 'Harap melengkapi profil anda terlebih dahulu !');
            Session::flash('error', true);

            return redirect()->route('pengaturan');
        }

        return $next($request);
    }

}
