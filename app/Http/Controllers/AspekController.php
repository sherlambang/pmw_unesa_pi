<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use PMW\Models\Aspek;

class AspekController extends Controller
{
    public function tambah(Request $request)
    {
        foreach (explode(PHP_EOL, $request->nama) as $item) {
            Aspek::create([
                'nama' => $item,
                'tahap' => $request->tahap
            ]);
        }

        return back()->with('message','Berhasil menambahkan aspek!');
    }

    public function hapus(Request $request)
    {
        Aspek::where('id', $request->id)->delete();

        return back()->with('message','Berhasil menghapus aspek!');
    }

    public function edit(Request $request)
    {
        $data = Aspek::find($request->id);
        $data->nama = $request->nama;
        $data->save();

        return back()->with('message','Berhasil merubah aspek!');
    }
    
}
