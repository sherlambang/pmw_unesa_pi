<?php

namespace PMW\Http\Controllers\Page;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMW\Facades\ExcelExport;
use PMW\Http\Controllers\Controller;
use PMW\Models\Proposal;

class AdminFakultasController extends Controller
{
    public function daftarProposal(Request $request)
    {
        $request->perHalaman = ($request->perHalaman < 5) ? 5 : $request->perHalaman;
        $request->lolos = ($request->lolos != 'tahap_1' && $request->lolos != 'tahap_2' && $request->lolos != 'lengkap') ? 'semua_proposal' : $request->lolos;
        $proposal = Proposal::proposalPerFakultas(Auth::user()->prodi()->jurusan()->id_fakultas);
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

        return view('admin.fakultas.daftarproposal', [
            'proposal' => $proposal->paginate($request->perHalaman),
            'lolos' => $request->lolos,
            'c' => 0,
            'perHalaman' => $request->perHalaman,
            'period' => $request->period
        ]);
    }

    public function unduhProposal(Request $request)
    {
        $request->lolos = ($request->lolos == 'semua_proposal') ? 'semua' : $request->lolos;
        $request->period = ($request->period == 'semua_periode') ? 'semua' : $request->period;
        return ExcelExport::unduhProposal(Auth::user()->prodi()->jurusan()->id_fakultas, $request->lolos, $request->period);
    }
}
