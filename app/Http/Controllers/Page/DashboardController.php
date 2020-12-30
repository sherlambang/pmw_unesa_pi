<?php

namespace PMW\Http\Controllers\Page;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PMW\Support\RequestStatus;
use PMW\Http\Controllers\Controller;
use PMW\Models\Penilaian;
use PMW\Models\Aspek;

/**
 * Class DashboardController
 * Class ini berfungsi untuk menampilkan halaman dasbor
 * sesuai dengan hak akses penggunanya
 *
 * @package PMW\Http\Controllers\Page
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 */
class DashboardController extends Controller
{

    /**
     * Mengecek jenis pengguna dan mecocokkan halaman
     * dasbor sesuai hak aksesnya
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        if(Auth::user()->isSuperAdmin())
            return $this->superAdmin();
        else if(Auth::user()->isAdminUniversitas())
            return $this->adminUniversitas();
        else if(Auth::user()->isAdminFakultas())
            return $this->adminFakultas();
        else if(Auth::user()->isDosenPembimbing())
            return $this->dosen();
        else if(Auth::user()->isReviewer())
            return $this->reviewer();
        else if(Auth::user()->isMahasiswa())
            return $this->mahasiswa();
        else
            return $this->tanpaHakAkses();
    }

    /**
     * Halaman dasbor untuk mahasiswa
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mahasiswa()
    {
        return view('mahasiswa.dashboard', [
            'undangan' => Auth::user()->mahasiswa()->undanganTimAnggota(),
        ]);
    }

    /**
     * Menampilkan halaman dasbor untuk reviewer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reviewer()
    {
        $daftarproposal = [];
        $daftarproposal = $this->pushDataUntukReviewer($daftarproposal);
        
        return view('dosen.dashboard', [
            'daftarproposal' => $daftarproposal
        ]);
    }

    /**
     * Halaman dasbor untuk dosen pembimbing
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dosen()
    {
        $data = [];

        $data = $this->pushDataUntukDosenPembimbing($data);
        $data['daftarproposal'] = [];

        if(Auth::user()->isReviewer())
            $data['daftarproposal'] = $this->pushDataUntukReviewer($data['daftarproposal']);
        
        return view('dosen.dashboard', $data);
    }

    /**
     * Halaman dasbor untuk admin fakultas
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminFakultas()
    {
        return view('admin.fakultas.dashboard');
    }

    /**
     * Halaman dasbor untuk admin universitas
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminUniversitas()
    {
        return view('admin.univ.dashboard');
    }

    /**
     * Halaman dasbor untuk superadmin
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function superAdmin()
    {
        $data = [];
        if(Auth::user()->isDosenPembimbing())
            $data = $this->pushDataUntukDosenPembimbing($data);

        return view('admin.super.dashboard', $data);
    }

    /**
     * Halaman dasbor untuk pengguna yang belum memiliki hak akses
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function tanpaHakAkses()
    {
        if(is_null(Auth::user()->nama)) {
            Session::flash('message', 'Pastikan anda melengkapi profil terlebih dahulu');
            Session::flash('error', true);
            return redirect()->route('pengaturan');
        }

        return view('dashboard');
    }

    /**
     * Mendapatkan data untuk reviwer untuk dikirimkan ke dashboard pengguna
     *
     * @param $data
     * @return array
     */
    private function pushDataUntukReviewer($data)
    {
        $data['tahap1'] = Auth::user()->review()->where('tahap', 1);
        $data['tahap2'] = Auth::user()->review()->where('tahap', 2);
        // Mendaptkan jumlah proposla yang sudah dinilai pada tahap 1
        $data['sudahdinilai']['tahap1'] = Penilaian::whereIn('id_review', $data['tahap1']->pluck('review.id'))->distinct()->count() / Aspek::where('tahap', 1)->count();
        // Mendaptkan jumlah proposla yang sudah dinilai pada tahap 2
        $data['sudahdinilai']['tahap2'] = Penilaian::whereIn('id_review', $data['tahap2']->pluck('review.id'))->distinct()->count() / Aspek::where('tahap', 2)->count();

        return $data;
    }

    /**
     * Mendapatkan data untuk dosen pembimbing untuk dikirimkan ke dashboard pengguna
     *
     * @param $data
     * @return array
     */
    private function pushDataUntukDosenPembimbing($data)
    {
        $data['undangan'] = Auth::user()->bimbingan()->where('status_request', RequestStatus::REQUESTING);
        $data['bimbingan'] = Auth::user()->bimbingan()->where('status_request', RequestStatus::APPROVED)->limit(3);

        return $data;
    }

}
