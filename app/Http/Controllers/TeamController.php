<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use PMW\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use PMW\Models\Proposal;

class TeamController extends Controller
{

    /**
     * Menghapus anggota tim
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hapusAnggota(Request $request)
    {
        $anggota = User::find($request->anggota);

        if(!is_null($anggota)){
            Mahasiswa::where('id_pengguna',$anggota->id)
                ->delete();

            return response()->json([
                'message' => 'Berhasil menghapus anggota !',
                'type' => 'success'
            ]);
        }

        return response()->json([
            'message' => 'Tidak bisa menghapus anggota !',
            'type' => 'error'
        ]);
    }

    public function konfirmasiTim(Request $request)
    {
        $mahasiswa = Auth::user();

        $mahasiswa->jadikanKetua();

        if($mahasiswa->mahasiswa()->punyaProposalKosong()) {
            $mahasiswa->mahasiswa()->proposal()->update([
                'konfirmasi_tim' => true
            ]);
        }
        else {
            $proposal = Proposal::create([
                'lolos' => false,
                'konfirmasi_tim' => true
            ]);

            $mahasiswa->mahasiswa()->update([
                'id_proposal' => $proposal->id
            ]);
        }

        return response()->json([
            'error' => 0,
            'message' => 'Berhasil mengomfirmasi tim/kelompok anda !'
        ]);
    }

}
