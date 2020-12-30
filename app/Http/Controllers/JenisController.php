<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use PMW\Models\Jenis;

class JenisController extends Controller
{
    public function edit(Request $request)
    {
        Jenis::find($request->id)->update([
            'nama' => $request->nama
        ]);

        return back()->with('message', 'Berhasil mengubah jenis');
    }

    public function tambah(Request $request)
    {
        foreach (explode(PHP_EOL, $request->nama) as $item) {
            Jenis::create([
                'nama' => $item
            ]);
        }

        return back()->with('message','Berhasil menambahkan jenis!');
    }

    public function hapus(Request $request)
    {
        Jenis::find($request->id)->delete();

        return back()->with('message', 'Berhasil menghapus jenis.');
    }
}
