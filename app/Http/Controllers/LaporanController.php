<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMW\Models\Laporan;
use PMW\Models\Proposal;
use PMW\Support\FileHandler;
use PMW\Facades\FileHandler as FH;

/**
 * Controller untuk mengatur aktifitas dari Laporan Kemajuan
 *
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers
 */
class LaporanController extends Controller
{

    /**
     * jenis ekstensi yang dinggap valid untuk diunggah
     *
     * @var array
     */
    private $validExtension = [
        'pdf'
    ];

    /**
     * lokasi dimana file akan disimpan, dengan storage/app/public
     * sebagai root
     *
     * @var string
     */
    private $dir = 'laporan/kemajuan';

    /**
     * array untuk validasi
     *
     * @var array
     */
    private $validationArr = [
        'berkas' => 'required'
    ];

    use FileHandler;

    /**
     * Mengunggah laporan kemajuan
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unggah(Request $request)
    {
        $berkas = $request->file('berkas');

        // if ($this->bolehUnggah()) {

            $this->validate($request, $this->validationArr);

            if ($this->berkasValid($berkas)) {
                $file = $this->unggahBerkas($berkas);

                Laporan::create([
                    'id_proposal' => Auth::user()->mahasiswa()->proposal()->id,
                    'jenis' => Laporan::KEMAJUAN,
                    'direktori' => $file,
                    'keterangan' => $request->keterangan
                ]);

                return response()->json([
                   'message' => 'Berhasil mengunggah laporan kemajuan !',
                   'type' => 'success'
                ]);
            }

            return response()->json([
                'message' => 'Berkas tidak valid !',
                'type' => 'error'
            ]);
        // }

        // return response()->json([
        //    'message' => 'Gagal mengunggah laporan kemajuan !',
        //    'type' => 'error'
        // ]);
    }

    /**
     * Mengunduh laporan kemajuan
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function unduh(Request $request)
    {
        if ($request->has('id_proposal'))
            $laporan = Proposal::find($request->id_proposal)->laporanKemajuan();

        if (Auth::user()->isMahasiswa())
            $laporan = Auth::user()->mahasiswa()->proposal()->laporanKemajuan();

        return FH::download($this->dir, $laporan->direktori);
    }

}
