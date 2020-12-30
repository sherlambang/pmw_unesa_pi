<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use PMW\Models\Fakultas;

class FakultasController extends Controller
{
    public function tambah(Request $request)
    {
        foreach (explode(PHP_EOL, $request->nama) as $item) {
            Fakultas::create([
                'nama' => $item
            ]);
        }

        return back()->with('message', 'Berhasil menambahkan '.count(explode(PHP_EOL, $request->nama)).' fakultas!');
    }

    public function hapus(Request $request)
    {
        $fakultas = Fakultas::find($request->id);
        $nama = $fakultas->nama;
        $fakultas->delete();

        return back()->with('message', 'Berhasil menghapus fakultas '.$nama);
    }

    public function edit(Request $request)
    {
        $data = Fakultas::find($request->id);
        $nama = $data->nama;
        $data->nama = $request->nama;
        $data->save();

        return back()->with('message', 'Berhasil mengedit '.$nama.' menjadi '.$data->nama.'!');
    }

    public function tambahCsv(Request $request)
    {
        $this->validate($request,[
            'csv' => 'required'
        ]);

        $file = fopen($request->file('csv')->getRealPath(),'r');
        $jumlah = 0;
        while(!feof($file))
        {
            $row = fgetcsv($file,0,' ');
            Fakultas::create([
                'nama' => $row[0],
            ]);
            $jumlah++;
        }
        fclose($file);

        return back()->with('message', 'Berhasil menambahkan '.$jumlah.' jurusan');
    }
}
