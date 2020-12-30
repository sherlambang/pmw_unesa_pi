<?php

namespace PMW\Http\Controllers\Page;

use Carbon\Carbon;
use PMW\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PMW\Models\Aspek;
use PMW\Models\Laporan;
use PMW\Models\Penilaian;
use Illuminate\Http\Request;

class ReviewerController extends Controller
{

    private $perPage = 10;

    /**
     * Melihat daftar proposal yang bisa dinilai atau telah dinilai
     * oleh reviewer tertentu
     *
     * @param int $tahap
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function daftarProposal(Request $request, $tahap = 1)
    {
        if ($tahap < 1 || $tahap > 2)
            return abort(404);

        $sudahDinilai = false;
        $daftarReviewPengguna = Auth::user()->review()->pluck('review.id');
        $reviewSudahDinilai = Penilaian::whereIn('id_review', $daftarReviewPengguna)
            ->distinct()
            ->pluck('id_review');

        if(!is_null(request()->get('sudahdinilai')) && request()->get('sudahdinilai'))
            $sudahDinilai = true;

        if ($sudahDinilai)
            $filtered = $reviewSudahDinilai;
        else{
            $filtered = Auth::user()->review()
                ->whereNotIn('review.id', $reviewSudahDinilai)
                ->pluck('review.id');
        }

        $daftarProposal = Auth::user()->review()
            ->where('tahap', $tahap)
            ->wherePivotIn('id', $filtered)
            ->when($request->periode && $request->periode != 'semua_periode', function ($query) use ($request) {
                $query->whereRaw('YEAR(proposal.created_at) = ' . $request->periode);
            })
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('proposal.judul', 'LIKE', '%'. $request->keyword .'%');
            })
            ->paginate($this->perPage);
        
        return view('dosen.reviewer.daftarproposal', [
            'daftarproposal' => $daftarProposal,
            'tahap' => $tahap,
            'period' => $request->has('period') ? $request->period : 'Semua Periode'
        ]);
    }

    public function daftarProposalFinal()
    {
        return view('dosen.reviewer.daftarproposalfinal', [
            'daftarproposal' => Auth::user()->review()->where('direktori_final', '!=', '')
        ]);
    }

    public function daftarLaporanKemajuan()
    {
        return view('dosen.reviewer.daftarlaporan', [
            'daftarlaporan' => Laporan::where('jenis', Laporan::KEMAJUAN)->whereIn('id_proposal', Auth::user()->review()->get()->pluck('id'))
        ]);
    }

    public function daftarLaporanAkhir()
    {
        return view('dosen.reviewer.daftarlaporan', [
            'daftarlaporan' => Laporan::where('jenis', Laporan::AKHIR)->whereIn('id_proposal', Auth::user()->review()->get()->pluck('id'))
        ]);
    }

    /**
     * Melihat nilai review
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lihatNilaiReview($id)
    {
        // Mengambil proposal sesuai id dari review
        $proposal = Auth::user()->review()
            ->wherePivot('id', $id)
            ->first();

        if (is_null($proposal))
            return abort(404);

        // Mengambil nilai dari proposal sesuai dengan id reviewer
        $penilaian = $proposal->daftarReview($proposal->pivot->tahap)
            ->where('id_pengguna', Auth::user()->id)
            ->first()
            ->penilaian();

        return view('dosen.reviewer.nilaiproposal', [
            'penilaian' => $penilaian,
            'proposal' => $proposal
        ]);
    }

    /**
     * Menampilkan halaman untuk menambah review dari sebuah proposal
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tambahReview($id)
    {
        $proposal = Auth::user()->review()->wherePivot('id', $id)->first();

        if (is_null($proposal))
            return abort(404);

        $tahap = $proposal->pivot->tahap;

        return view('dosen.reviewer.kelolareview', [
            'proposal' => $proposal,
            'daftaraspek' => Aspek::where('tahap', $tahap)->get(),
            'type' => 'tambah'
        ]);
    }

    public function editNilaiReview($id)
    {
        $proposal = Auth::user()->review()->wherePivot('id', $id)->first();

        if (is_null($proposal))
            return abort('404');

        $penilaian = $proposal->penilaian($id);

        $tahap = $proposal->pivot->tahap;        

        return view('dosen.reviewer.kelolareview', [
            'penilaian' => $penilaian,
            'daftaraspek' => Aspek::where('tahap', $tahap)->get(),
            'proposal' => $proposal,
            'type' => 'edit'
        ]);
    }

}
