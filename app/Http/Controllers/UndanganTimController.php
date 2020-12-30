<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMW\User;
use Illuminate\Support\Facades\Mail;
use PMW\Mail\UndanganTimMail;
use PMW\Models\Proposal;
use PMW\Models\Prodi;

/**
 * Controller ini berfungsi untuk mengatur atau melakuakn aksi yang
 * berkaitan dengan proses undangan mahasiswa ke mahasiswa lain dalam
 * hal untuk pembentukan sebuah tim pmw
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers
 */
class UndanganTimController extends Controller
{

    /**
     * Membuat undangan untuk mahasiswa lain agar bisa
     * bergabung dalam tim
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buatUndangan(Request $request)
    {
        $dari = Auth::user();
        $untuk = User::find($request->untuk);
        $prodi = Prodi::find($dari->id_prodi);

        if(!is_null($untuk) && $dari->id != $untuk->id)
        {
            if(Auth::user()->mahasiswa()->bisaKirimUndanganTim())
            {
                if(!$untuk->mahasiswa()->punyaUndanganTim($dari)) {
                    if(!$dari->mahasiswa()->punyaUndanganTim($untuk)){
                        
                        Auth::user()->mahasiswa()->undanganTimKetua()->attach($untuk->mahasiswa());
                        Mail::to($untuk->email)->send(new UndanganTimMail($dari->nama, $prodi->nama, $untuk->nama));
                        return response()->json([
                            'message' => 'Berhasil mengirim undangan !',
                            'error' => 0
                        ]);

                    }
                    else {
                        return response()->json([
                            'message' => 'Anda tidak bisa mengirim undangan ke pengguna ini !',
                            'error' => 4
                        ]);
                    }
                }
                else {
                    return response()->json([
                        'message' => 'Anda sudah pernah mengirim undangan ke mahasiswa ini !',
                        'error' => 3
                    ]);
                }
            }
            else {
                return response()->json([
                    'message' => 'Anda tidak bisa mengirim undangan !',
                    'error' => 1
                ]);
            }
        }
        return response()->json([
            'message' => 'Gagal mengirim undangan !',
            'error' => 2
        ]);
    }

    /**
     * Menerima undangan untuk bergabung dalam tim
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function terimaUndangan(Request $request)
    {
        $dari = User::find($request->dari);
        $untuk = Auth::user();

        // Memastikan bahwa user tidak sedang mengirim undangan dan
        // pengirim undangan bukan merupakan anggota, serta user
        // belum memiliki tim, serta tidak bisa menerima undangan jika anggota tim sudah
        // berjumlah 3 orang
        if($untuk->mahasiswa()->undanganTimKetua()->count() == 0 &&
            !$untuk->mahasiswa()->punyaTim())
        {
            if(!$dari->mahasiswa()->timLengkap()) {

                // Terima undangan
                if ($dari->mahasiswa()->punyaProposalKosong())
                    $proposal = $dari->mahasiswa()->proposal();
                 else {
                    $proposal = Proposal::create([
                        'lolos' => false,
                        'konfirmasi_tim' => false
                    ]);
                }

                // Mengupdate proposal dari pengirim undangan
                $dari->mahasiswa()->update([
                    'id_proposal' => $proposal->id
                ]);

                $dari->jadikanKetua();

                // Menambahkan id proposal pada user
                $untuk->mahasiswa()->update([
                    'id_proposal' => $proposal->id
                ]);

                if($dari->mahasiswa()->timLengkap()) {
                    $proposal->update([
                        'konfirmasi_tim' => true
                    ]);
                }

                $dari->mahasiswa()->undanganTimKetua()->detach($untuk->mahasiswa());

                return back()->with([
                    'message' => 'Anda berhasil bergabung dalam tim ' . $dari->nama,
                    'error' => 0
                ]);
            }
            else{
                return back()->with([
                    'message' => 'Pengirim undangan telah memiliki jumlah anggota yang mencukupi !',
                    'error' => 2
                ]);
            }
        }
        else
        {
            return back()->with([
                'message' => 'Anda tidak bisa menerima undangan ini !',
                'error' => 1
            ]);
        }
    }

    /**
     * Melakukan penolakan terhadap undangan pengirim
     * penolakan dilakukan dengan bukan menghapus, namun mengubah isi kolom
     * ditolak menjadi true
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tolakUndangan(Request $request)
    {
        $dari = User::find($request->dari);
        $untuk = Auth::user();

        $untuk->mahasiswa()->tolakUndanganTim($dari);

        return response()->json([
            'message' => 'Undangan telah ditolak !',
            'error' => 0
        ]);
    }

    /**
     * Menghapus undangan, namun hanya pengirim yang bisa menghapus undangannya
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hapusUndangan(Request $request)
    {
        $penerima = User::find($request->id)->mahasiswa();

        Auth::user()->mahasiswa()->undanganTimKetua()->detach($penerima);

        return response()->json([
            'message' => 'Undangan telah dihapus !',
            'error' => 0
        ]);
    }

    /**
     * Mengirim ulang undangan jika undangan sebelumnya telah
     * ditolak
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function kirimUlang(Request $request)
    {
        // mengubah isi kolom ditolak menjadi true
        // sehingga undangan dianggap belum diterima oleh user tujuan
        Auth::user()->mahasiswa()->undanganTimKetua()->updateExistingPivot($request->id, [
            'ditolak' => false
        ]);

        return response()->json([
            'message' => 'Undangan telah dikirim ulang !',
            'error' => 0
        ]);
    }

}
