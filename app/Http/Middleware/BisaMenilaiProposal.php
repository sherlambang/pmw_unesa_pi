<?php

namespace PMW\Http\Middleware;

use Closure;

class BolehMenilaiProposal
{
    /**
     * Mengecek apakah pengguna dapat melakukan penilaian pada tahap tertentu
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param int $tahap
     * @return mixed
     */
    public function handle($request, Closure $next, $tahap = 1)
    {
        if($request->user()->isReviewer() && Pengaturan::melewatiBatasPenilaian(2));
            return $next($request);

        return abort(404);
    }

}
