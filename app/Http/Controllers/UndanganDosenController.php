<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMW\Models\Bimbingan;
use PMW\Models\Proposal;
use PMW\Support\RequestStatus;
use Illuminate\Support\Facades\Mail;
use PMW\Mail\UndanganDosenMail;
use PMW\User;
use PMW\Models\Prodi;

/**
 * Controllers ini berfungsi untuk mengelola keperluan undangan
 * dari mahasiswa ke dosen dalam hal untuk pemilihan dosen
 * pembimbing
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers
 */
class UndanganDosenController extends Controller
{

    /**
     * Mengirim undangan dari mahasiswa untuk calon dosen pembimbing
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function kirimUndangan(Request $request)
    {
        $dosen = User::find($request->dosen);
        $proposal = Auth::user()->mahasiswa()->proposal();
        $dari = Auth::user();
        $prodi = Prodi::find($dari->id_prodi);

        Mail::to($dosen->email)->send(new UndanganDosenMail($dari->nama, $prodi->nama, $dosen->nama));
        $dosen->bimbingan()->attach($proposal, [
            'status_request' => RequestStatus::REQUESTING
        ]);

        

        return response()->json([
            'message' => 'Berhasil mengirim undangan !',
            'error' => 0
        ]);
    }

    /**
     * Mengirim ulang undangan ke calon dosen pembimbing jika undangan
     * sebelumnya ditolak
     *
     * @param Request $request
     * @return void
     */
    public function kirimUlang(Request $request)
    {
        $dosen = User::find($request->dosen);
        $proposal = Auth::user()->mahasiswa()->proposal();
        $dari = Auth::user();
        $prodi = Prodi::find($dari->id_prodi);

        Mail::to($dosen->email)->send(new UndanganDosenMail($dari->nama, $prodi->nama, $dosen->nama));

        $dosen->bimbingan()->updateExistingPivot($proposal->id, [
            'status_request' => RequestStatus::REQUESTING
        ]);

        return response()->json([
            'message' => 'Berhasil mengirim ulang !',
            'error' => 0
        ]);
    }

    /**
     * Menghapus undangan
     *
     * @param Request $request
     * @return void
     */
    public function hapus(Request $request)
    {
        $dosen = User::find($request->dosen);
        $proposal = Auth::user()->mahasiswa()->proposal();

        $dosen->bimbingan()->detach($proposal);

        return response()->json([
            'message' => 'Berhasil membatalkan dan menghapus undangan !',
            'error' => 0
        ]);
    }

    /**
     * Menerima undangan oleh dosen pembimbing
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function terimaUndangan(Request $request)
    {
        $proposal = Proposal::find($request->proposal);

        $proposal->tambahPembimbing(Auth::user());

        return response()->json([
            'message' => 'Anda telah menjadi pembimbing tim ini !',
            'error' => 0
        ]);
    }

    /**
     * Melakukan penolakan terhadap undangan dosen pembimbing
     *
     * @param Request $request
     * @return void
     */
    public function tolak(Request $request)
    {
        $proposal = Proposal::find($request->proposal);

        Auth::user()->bimbingan()->updateExistingPivot($request->proposal, [
            'status_request' => RequestStatus::REJECTED
        ]);

        return response()->json([
            'message' => 'Berhasil menolak undangan !',
            'error' => 0
        ]);
    }

}
