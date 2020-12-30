<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PMW\Models\HakAkses;
use PMW\Support\RequestStatus;
use PMW\User;

/**
 * Controller ini berfungsi untuk melakukan aksi yang berkaitan
 * dengan hak akses
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers
 */
class HakAksesController extends Controller
{

    /**
     * Melakukan request untuk menjadi reviewer
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestReviewer()
    {
        $reviewer = HakAkses::where('nama', HakAkses::REVIEWER)->first();

        return $this->requestHakAkses($reviewer);
    }

    /**
     * Melakukan request untuk menjadi dosen pembimbing
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestDosenPembimbing()
    {
        $pembimbing = HakAkses::where('nama', HakAkses::DOSEN_PEMBIMBING)->first();

        return $this->requestHakAkses($pembimbing);
    }

    /**
     * Melakukan request untuk hak akses tertentu
     *
     * @param  HakAkses $hakAkses
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestHakAkses($hakAkses)
    {
        $pengguna = Auth::user();

        // Jika user telah memiliki hak akses tersebut
        // maka tidak perlu request lagi, buat apa
        if (!$pengguna->hasRole($hakAkses->nama)) {
            // Jika request pernah di tolak, maka cukup melakukan
            // update pada tabel dengan mengubah status request
            if ($pengguna->hakAksesDitolak($hakAkses)) {
                $pengguna->hakAksesPengguna()->updateExistingPivot($hakAkses->id ,[
                    'status_request' => RequestStatus::REQUESTING
                ]);
            } else {
                $pengguna->hakAksesPengguna()->attach($hakAkses, [
                    'status_request' => RequestStatus::REQUESTING
                ]);
            }

            Session::flash('message', 'Berhasil meminta hak akses');
            Session::flash('error', false);

            return back();
        }

        Session::flash('message', 'Gagal meminta hak akses tersebut !');
        Session::flash('error', true);
        
        return back();
    }

    /**
     * Menerima request terhadap hak akses yang diminta oleh user tertentu
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function terimaRequest(Request $request)
    {
        $pengguna = User::find($request->id_pengguna);

        $pengguna->hakAksesPengguna()->updateExistingPivot($request->id_hak_akses, [
            'status_request' => RequestStatus::APPROVED
        ], false);

        return back();
    }

    /**
     * Menolak request hak akses yang diajukan user dan hak akses tertentu
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tolakRequest(Request $request)
    {
        $pengguna = User::find($request->id_pengguna);

        $pengguna->hakAksesPengguna()->updateExistingPivot($request->id_hak_akses, [
            'status_request' => RequestStatus::REJECTED
        ], false);

        return back();
    }

}
