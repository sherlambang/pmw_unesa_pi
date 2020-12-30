<?php

namespace PMW\Http\Controllers\Page;

use Illuminate\Http\Request;
use PMW\Http\Controllers\Controller;
use PMW\Models\Fakultas;
use PMW\Models\Proposal;
use PMW\Facades\ExcelExport;

class AdminUniversitasController extends Controller
{
    public function daftarProposal(Request $request)
    {
        $request->perHalaman = ($request->perHalaman < 5) ? 5 : $request->perHalaman;
        $request->lolos = ($request->lolos != 'tahap_1' && $request->lolos != 'tahap_2' && $request->lolos != 'lengkap') ? 'semua_proposal' : $request->lolos;
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
        if($request->lolos == 'lengkap') {
            $proposal = $proposal->filter(function ($value, $key) {
                return !is_null($value->jenis_id);
            });
        }
        if ($request->period != 'semua_periode'){
            $proposal = $proposal->filter(function ($value, $key) use ($request){
                return Carbon::parse($value->created_at)->year == $request->period;
            });
        }
//        dd($proposal);
        return view('admin.univ.daftarproposal', [
            'proposal' => $proposal->paginate($request->perHalaman),
            'daftar_fakultas' => Fakultas::all(),
            'fakultas' => $nama_fakultas,
            'lolos' => $request->lolos,
            'c' => 0,
            'perHalaman' => $request->perHalaman,
            'period' => $request->period
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
}