<?php

namespace PMW\Http\Controllers\Page;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PMW\Http\Controllers\Controller;
use PMW\Models\Aspek;
use PMW\Models\Fakultas;
use PMW\Models\HakAkses;
use PMW\Models\Jurusan;
use PMW\Models\Pengaturan;
use PMW\Models\Prodi;
use PMW\Models\Proposal;
use PMW\Support\RequestStatus;
use PMW\User;
use Illuminate\Support\Facades\DB;
use PMW\Facades\ExcelExport;

class SuperAdminController extends Controller
{
    public function pengaturan()
    {
        return view('admin.super.pengaturan', [
            'pengaturan' => Pengaturan::all(),
            'aspek' => Aspek::all(),
            'c' => 0,
            'n' => 0
        ]);
    }

    public function tampilDataPengguna(Request $request)
    {
        $request->fakultas = (Fakultas::checkName(ucwords(str_replace('_',' ', $request->fakultas)))) ? $request->fakultas : 'semua_fakultas';
        $request->perHalaman = ($request->perHalaman < 5) ? 5 : $request->perHalaman;
        $pengguna = ($request->fakultas == 'semua_fakultas') ? User::orderBy('nama')->get() : User::perFakultas(ucwords(str_replace('_',' ', $request->fakultas)));
        if ($request->role != 'semua_hak_akses'){
            $dump = $pengguna;
            $pengguna = [];
            foreach ($dump as $item){
                if (User::find($item->id)->hasRole(ucwords(str_replace('_',' ', $request->role))))
                    array_push($pengguna, $item);
            }
        }
        $pengguna = collect($pengguna);
        if ($request->q != '[]')
            $pengguna = $pengguna->filter(function ($value, $key) use ($request){
                return str_contains($value->id, $request->q) || str_contains(strtolower($value->nama), strtolower($request->q));
            });
        return view('admin.super.daftarpengguna', [
            'user'              => $pengguna->paginate($request->perHalaman),
            'hak_akses'         => HakAkses::orderBy('id')->get(),
            'daftar_fakultas'   => Fakultas::all(),
            'fakultas'          => $request->fakultas,
            'role'              => $request->role,
            'c'                 => 0,
            'perHalaman'        => $request->perHalaman,
            'q'                 => $request->q
        ]);
    }

    public function tampilDataFakultas()
    {
        return view('admin.super.daftarfakultas', [
            'fakultas' => Fakultas::orderBy('nama')->get(),
            'c' => 0
        ]);
    }

    public function tampilDataJurusan(Request $request)
    {
        $request->fakultas = (Fakultas::checkName(ucwords(str_replace('_',' ', $request->fakultas)))) ? $request->fakultas : 'semua_fakultas';
        $request->perHalaman = ($request->perHalaman < 5) ? 5 : $request->perHalaman;
        $nama_fakultas = ucwords(str_replace('_',' ',$request->fakultas));
        if (Fakultas::checkName($nama_fakultas)){
            $jurusan = Jurusan::where('id_fakultas',Fakultas::getIdByName($nama_fakultas))->orderBy('nama');
        }
        else{
            $jurusan = Jurusan::orderBy('id_fakultas')->orderBy('nama');
        }
        return view('admin.super.daftarjurusan', [
            'fakultas' => $request->fakultas,
            'jurusan' => $jurusan->paginate($request->perHalaman),
            'daftarfakultas' => Fakultas::orderBy('nama')->get(),
            'c' => 0,
            'perHalaman' => $request->perHalaman
        ]);
    }

    public function tampilDataProdi(Request $request)
    {
        $request->jurusan = (Jurusan::checkName(ucwords(str_replace('_',' ', $request->jurusan)))) ? $request->jurusan : 'semua_jurusan';
        $request->perHalaman = ($request->perHalaman < 5) ? 5 : $request->perHalaman;
        $nama_jurusan = ucwords(str_replace('_',' ',$request->jurusan));
        if (Jurusan::checkName($nama_jurusan)){
            $prodi = Prodi::where('id_jurusan',Jurusan::getIdByName($nama_jurusan))->orderBy('nama');
        }
        else{
            $prodi = Prodi::orderBy('id_jurusan')->orderBy('nama');
        }
        return view('admin.super.daftarprodi', [
            'prodi' => $prodi->paginate($request->perHalaman),
            'daftarjurusan' => Jurusan::orderBy('id_fakultas')->orderBy('nama')->get(),
            'no' => 0,
            'perHalaman' => $request->perHalaman,
            'jurusan' => $request->jurusan
        ]);
    }

    public function tampilDataAspek()
    {
        return view('admin.super.daftaraspek', [
            'aspek' => Aspek::all()
        ]);
    }

    public function tampilDataProposal(Request $request)
    {
        $request->perHalaman = ($request->perHalaman < 5) ? 5 : $request->perHalaman;
        $request->lolos = ($request->lolos != 'tahap_1' && $request->lolos != 'tahap_2') ? 'semua_proposal' : $request->lolos;
        $nama_fakultas = ucwords(str_replace('_', ' ', $request->fakultas));
        $nama_fakultas = (Fakultas::checkName($nama_fakultas)) ? $nama_fakultas : 'semua_fakultas';
        $proposal = ($nama_fakultas == 'semua_fakultas') ? Proposal::all() : Proposal::proposalPerFakultas(Fakultas::where('nama', $nama_fakultas)->first()->id);
        if ($request->lolos == 'tahap_1' || $request->lolos == 'tahap_2'){
            $dump = $proposal;
            $proposal = [];
            foreach ($dump as $item){
                if (\PMW\Models\Proposal::find($item->id)->lolos(explode('_',$request->lolos)[1])){
                    array_push($proposal, $item);
                }
            }
        }
        $proposal = collect($proposal);
        if ($request->period != 'semua_periode'){
            $proposal = $proposal->filter(function ($value, $key) use ($request){
                return Carbon::parse($value->created_at)->year == $request->period;
            });
        }
        if (!is_null($request->q)){
            $proposal = $proposal->filter(function ($value, $key) use ($request){
                if ( strval($request->q) == strval(intval($request->q)) ) {
                  return intval($value->id) == intval($request->q);
                }
                else{
                    return str_contains(strtolower($value->judul), strtolower($request->q));
                }
            });
        }
        return view('admin.super.daftarproposal', [
            'proposal' => $proposal->paginate($request->perHalaman),
            'daftar_fakultas' => Fakultas::all(),
            'fakultas' => $nama_fakultas,
            'lolos' => $request->lolos,
            'c' => 0,
            'perHalaman' => $request->perHalaman,
            'period' => $request->period,
            'q' => $request->q
        ]);
    }

    public function tampilRequestHakAkses(Request $request)
    {
        return view('admin.super.daftarrequesthakakses', ['pengguna' => HakAkses::permintaanHakAkses()]);
    }

    /**
     * Melakukan pengeditan reviewer dari sebuah proposal
     *
     * @param $idproposal
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editReviewer($idproposal)
    {
        $daftarreviewer = HakAkses::where('nama', HakAkses::REVIEWER)->first()->pengguna()->where('status_request', RequestStatus::APPROVED)->get();

        $reviewerTahap1 = Proposal::where('id', $idproposal)->first()->reviewer()->wherePivot('tahap', 1);

        return view('admin.super.setreviewer', [
            'daftarreviewer' => $daftarreviewer,
            'daftarreviewer2' => HakAkses::where('nama', HakAkses::REVIEWER)->first()->pengguna()->where('status_request', RequestStatus::APPROVED)->get(),
            'proposal' => Proposal::find($idproposal),
            'oldreviewer' => [
                'tahap1' => $reviewerTahap1,
                'tahap2' => Proposal::where('id', $idproposal)->first()->reviewer()->wherePivot('tahap', 2)
            ]
        ]);
    }

    public function unduhProposal(Request $request)
    {
        $nama_fakultas = ucwords(str_replace('_', ' ', $request->fakultas));
        $fakultas = Fakultas::where('nama', $nama_fakultas)->first();
        $request->lolos = ($request->lolos == 'semua_proposal') ? 'semua' : $request->lolos;
        $request->period = ($request->period == 'semua_periode') ? 'semua' : $request->period;
        return ExcelExport::unduhProposal((is_null($fakultas)) ? $fakultas : $fakultas->id, $request->lolos, $request->period);
    }

    public function unduhPengguna(Request $request)
    {
        $nama_fakultas = ucwords(str_replace('_', ' ', $request->fakultas));
        return ExcelExport::unduhDaftarPengguna($nama_fakultas, $request->role);
    }

}
