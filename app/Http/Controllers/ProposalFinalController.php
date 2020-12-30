<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PMW\Support\FileHandler;
use PMW\User;
use PMW\Models\Proposal;
use PMW\Facades\FileHandler as FH;

/**
 * Controller ini berfungsi untuk mengatur atau melakukan aksi
 * yang berkaitan dengan proses pengunggahan atau pengeditan proposal
 * final
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers
 */
class ProposalFinalController extends Controller
{

    /**
     * Format atau ekstensi file yang diperbolehkan untuk diunggah
     *
     * @var array
     */
    private $validExtension = [
        'pdf'
    ];

    private $dir = 'proposal/final';

    use FileHandler;

    /**
     * Proses pengunggahan proposal final
     *
     * @param Request $request
     * @return void
     */
    public function unggah(Request $request)
    {
        if($this->bolehUnggah(true))
        {
            if($this->berkasValid($request->file('berkas')))
            {

                $this->hapusProposalSebelumnya();

                $proposal = Auth::user()->mahasiswa()->proposal();

                $berkas = $this->unggahBerkas($request->file('berkas'));

                $proposal->update([
                    'direktori_final'   => $berkas,
                    'usulan_dana'       => $request->usulan_dana,
                    'judul'             => $request->judul,
                    'abstrak'           => $request->abstrak,
                    'jenis_id'          => $request->jenis_usaha,
                    'keyword'           => $request->keyword
                ]);

                return response()->json([
                    'message' => 'Berhasil mengunggah proposal final !',
                    'error' => false,
                    'redirect' => route('proposal')
                ]);
            }
            else {
                return response()->json([
                    'message' => 'Format file tidak valid !',
                    'error' => true
                ]);
            }
        }
        else {
            return response()->json([
                'message' => 'Anda tidak bisa mengunggah proposal !',
                'error' => true
            ]);
        }
    }

    /**
     * Menghapus file proposal final
     *
     * @return void
     */
    public function hapusProposalSebelumnya()
    {
        $proposal = Auth::user()->mahasiswa()->proposal();

        // Jika pernah mengunggah proposal
        if(!is_null($proposal->direktori_final)) {
            FH::delete($this->dir, $proposal->direktori_final);
        }
    }

    /**
     * Mengunduh proposal final
     *
     * @param Request $request
     * @return
     */
    public function unduh(Request $request)
    {
        $proposal = Proposal::find($request->id);

        if(Auth::user()->isMahasiswa())
            $proposal = Auth::user()->mahasiswa()->proposal();

        // proses unduh
        return FH::download($this->dir, $proposal->direktori_final); 
    }

}
