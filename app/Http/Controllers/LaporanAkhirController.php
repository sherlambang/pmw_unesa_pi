<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use PMW\Support\FileHandler;
use PMW\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use PMW\Models\Proposal;
use PMW\Facades\FileHandler as FH;

/**
 * Controller ini berfungsi untuk melakukan aksi yang berkaitan
 * dengan laporan akhir proposal
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers
 */
class LaporanAkhirController extends Controller
{

    /**
     * Ekstensi file yang dianggap valid untuk diunggah
     *
     * @var array
     */
    private $validExtension = [
        'pdf'
    ];

    /**
     * Lokasi dimana file akan diletakkan
     *
     * @var string
     */
    private $dir = 'laporan/akhir';

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
     * Mengunggah laporan akhir
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unggah(Request $request)
    {
        $berkas = $request->file('berkas');

            if($this->berkasValid($berkas))
            {
                $file = $this->unggahBerkas($berkas);

                Laporan::create([
                    'id_proposal' => Auth::user()->mahasiswa()->proposal()->id,
                    'jenis' => Laporan::AKHIR,
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
    }

    /**
     * Mengunduh laporan akhir
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function unduh(Request $request)
    {
        if(!is_null($request->id_proposal))
            $laporan = Proposal::find($request->id_proposal)->laporanAkhir();

        if(Auth::user()->isMahasiswa())
            $laporan = Auth::user()->mahasiswa()->proposal()->laporanAkhir();

        return FH::download($this->dir, $laporan->direktori);
    }

}
