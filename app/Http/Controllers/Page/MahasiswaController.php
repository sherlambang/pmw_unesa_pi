<?php

namespace PMW\Http\Controllers\Page;

use Illuminate\Http\Request;
use PMW\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Controller ini menampilkan halaman-halaman yang
 * akan ditampilkan untuk pengguna dengan hak akses mahasiswa
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers\Page
 */
class MahasiswaController extends Controller
{

    /**
     * Menampilkan halaman untuk info tim
     *
     * @return \Illuminate\View\View
     */
    public function infoTim()
    {
        $view = view('mahasiswa.infotim');

        if(Auth::user()->mahasiswa()->punyaTim() && Auth::user()->mahasiswa()->proposal()->punyaPembimbing())
            $view->with('pembimbing', Auth::user()->mahasiswa()->proposal()->pembimbing());

        return $view;
    }

    /**
     * Menampilkan halaman laporan kemajuan maupun final
     * method ini digunakan jika pengguna adalah anggota tim (bukan ketua)
     *
     * @return \Illuminate\View\View
     */
    public function laporan()
    {
        $view = view('mahasiswa.laporan');

        if(!is_null(Auth::user()->mahasiswa()->proposal()->laporanKemajuan()))
            $view->with('laporankemajuan', Auth::user()->mahasiswa()->proposal()->laporanKemajuan());

        if(!is_null(Auth::user()->mahasiswa()->proposal()->laporanAkhir()))
            $view->with('laporanakhir', Auth::user()->mahasiswa()->proposal()->laporanAkhir());

        return $view;
    }

    /**
     * Menampilkan halaman daftar logbook dari tim pengguna
     *
     * @return \Illuminate\View\View
     */
    public function logbook()
    {
        $logbook = [];
        
        if(Auth::user()->mahasiswa()->punyaProposal() && Auth::user()->mahasiswa()->proposal()->lolos())
            $logbook = Auth::user()->mahasiswa()->proposal()->logbook()->paginate(15);

        return view('mahasiswa.logbook',[
           'daftarlogbook' => $logbook
        ]);
    }

}
