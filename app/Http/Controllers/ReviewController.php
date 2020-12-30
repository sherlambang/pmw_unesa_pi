<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PMW\Models\Aspek;
use PMW\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use PMW\Models\Review;

/**
 * Controller ini berfungsi untuk melakukan penambahan maupun
 * penegeditan sebuah review/penilaian
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers
 */
class ReviewController extends Controller
{

    /**
     * Menambah review untuk sebuah proposal
     * isi dari variabel $request :
     * $request->input('nilai.*) => berisi daftar nilai pada semua aspek (radio button)
     * 
     * @param Request $request
     * @param int $idreview
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tambah(Request $request, $idreview)
    {
        $review = Review::find($idreview);

        $daftarAspek = Aspek::where('tahap', $review->tahap)->get();

        // mencocokkan jumlah nilai aspek yang dikirim dengan jumlah aspek yang ada
        if (count($daftarAspek) === count($request->input('nilai.*'))) {

            // melakukan validasi
            $this->validate($request, [
                'komentar' => 'required'
            ]);

            // Menambahkan nilai per aspek
            foreach ($daftarAspek as $aspek) {
                if ($request->has('nilai.' . $aspek->id)) {
                    $review->penilaian()->attach(Aspek::find($aspek->id), [
                        'nilai' => $request->input('nilai.' . $aspek->id)
                    ]);
                }
            }

            // Menambah komentar
            $review->update([
                'komentar' => $request->komentar
            ]);
        }
        else {
            return back()->withErrors([
                'message' => 'Pastikan anda mengisi nilai untuk seluruh aspek !'
            ])->withInput();
        }

        return redirect()->route('daftar.proposal.reviewer', [
            'tahap' => $review->tahap,
            'sudahdinilai' => true
        ]);
    }

    /**
     * Mengedit review dari proposal tertentu
     *
     * @param Request $request
     * @param int $idreview
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $idreview)
    {
        $review = Review::find($idreview);

        $daftarAspek = Aspek::where('tahap', $review->tahap)->get();

        if (count($daftarAspek) === count($request->input('nilai.*'))) {

            // validasi
            $this->validate($request, [
                'komentar' => 'required'
            ]);

            // Menambahkan nilai per aspek
            foreach ($daftarAspek as $aspek) {
                if ($request->has('nilai.' . $aspek->id)) {
                    $review->ubahNilai(Aspek::find($aspek->id), $request->input('nilai.' . $aspek->id));
                }
            }

            // Menambah komentar
            $review->update([
                'komentar' => $request->komentar
            ]);
        }
        else {
            return back()->withErrors([
                'message' => 'Pastikan anda mengisi nilai untuk seluruh aspek !'
            ])->withInput();
        }

        Session::flash('message', 'Berhasil mengedit nilai !');
        Session::flash('error', false);

        return back();
    }

}
