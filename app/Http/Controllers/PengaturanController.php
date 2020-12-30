<?php

namespace PMW\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PMW\Models\Pengaturan;

class PengaturanController extends Controller
{
    public function edit(Request $request)
    {
        $pengaturan = Pengaturan::find($request->id);
        if ($pengaturan->nama == 'Nilai minimum proposal'){
            $this->validate($request, [
                'keterangan' => 'required|integer|between:0,100'
            ]);
        }
        else{
            $this->validate($request, [
                'keterangan' => 'required|max:19'
            ]);
            if ($pengaturan->nama == 'Batas pengumpulan proposal final'){
                $unfinal = Pengaturan::where('nama', 'Batas pengumpulan proposal')->first();
                $waktuUnfinal = Carbon::parse($unfinal->keterangan);
                $waktuFinal = Carbon::parse($request->keterangan);
                if ($waktuUnfinal->greaterThan($waktuFinal))
                    return back()->with('message', 'Maaf, waktu pengumpulan proposal final tidak boleh sebelum pengumpulan proposal yang belum final!');
            }
        }

        $pengaturan->update([
            'keterangan' => $request->keterangan
        ]);

        return back()->with('message', 'Pengaturan berhasil disimpan!');
    }
}
