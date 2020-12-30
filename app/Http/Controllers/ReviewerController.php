<?php

namespace PMW\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PMW\Models\Pengaturan;
use PMW\Models\Proposal;
use PMW\User;

/**
 * Controller ini berfungsi untuk mengatur atau melakukan aksi
 * yang berkaitan reviewer seperti pengelolaan reviewer untuk sebuah
 * proposal
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers
 */
class ReviewerController extends Controller
{

    /**
     * Mengelola reviewer dari sebuah proposal
     * isi dari $requst adalah sebagai berikut :
     * $request->tahap => int, tahap yang diinginkan
     * $request->daftar_pengguna => string, daftar reviewer
     *
     * @param Request $request
     * @param $idproposal
     */
    public function kelola(Request $request, $idproposal)
    {
        $proposal = Proposal::find($idproposal);

        $tahap = $request->tahap;

        // melakukan validasi waktu
        $this->validasiWaktu($tahap);

        // Daftar reviewer yang dikirim oleh user
        $daftarCalonReviewer = explode(',', $request->daftar_pengguna);

        // Daftar reviewer lama
        $daftarReviewerLama = $proposal->reviewer()->wherePivot('tahap',$tahap)->pluck('id_pengguna')->toArray();

        // Daftar reviewer yang nantinya akan di hapus
        $daftarReviewerLengser = array_diff($daftarReviewerLama, $daftarCalonReviewer);

        // Daftar reviewer baru
        $daftarReviewerBaru = array_diff($daftarCalonReviewer, $daftarReviewerLama);

        // Menghapus reviewer dari proposal tertentu
        foreach ($daftarReviewerLengser as $index => $idpengguna) {
            $pengguna = User::find($idpengguna);
            $proposal->reviewer()->wherePivot('tahap',$tahap)->detach($pengguna);
        }

        // Menambah reviewer ke proposal tertentu
        foreach ($daftarReviewerBaru as $index => $idpengguna) {
            $pengguna = User::find($idpengguna);
            $proposal->reviewer()->attach($pengguna, [
                'tahap' => $tahap
            ]);
        }
    }

    /**
     * @param $tahap
     * @return \Illuminate\Http\RedirectResponse|boolean
     */
    public function validasiWaktu($tahap)
    {
        // jika waktu sekarang melebihi atau merupakan tanggal bahwa
        // penilaian telah selesai sesuai tahap tertentu, maka
        // tidak bisa mengubah reviewer
        if(Pengaturan::melewatiBatasPenilaian($tahap)) {
            return back()->withErrors([
                'message' => 'Tidak bisa mengubah reviewer, !'
            ]);
        }

        return true;
    }

}
