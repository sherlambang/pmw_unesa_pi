<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use PMW\Models\Jurusan;
use PMW\Models\Prodi;

class ProdiController extends Controller
{
    public function tambah(Request $request)
    {
        foreach (explode(PHP_EOL, $request->nama) as $item){
            Prodi::create([
                'nama'          => $item
            ]);
        }

        return back()->with('message', 'Berhasil menambahkan '.count(explode(PHP_EOL, $request->nama)).' prodi!');
    }

    public function hapus(Request $request)
    {
        $prodi = Prodi::find($request->id);
        $nama = $prodi->nama;
        $prodi->delete();

        return back()->with('message', 'Berhasil menghapus Jurusan '.$nama.'!');
    }

    public function edit(Request $request)
    {
        $data = Prodi::find($request->id);
        $data->nama = $request->nama;
        $data->id_jurusan = $request->id_jurusan;
        $data->save();

        return back()->with('message', 'Berhasil mengubah '.$data->nama.'!');
    }

    public function daftarBerdasarkanJurusan(Request $request)
    {
        $prodi = Prodi::where('id_jurusan',$request->jurusan)->get();

        return response()->json($prodi);
    }

    public function tambahCsv(Request $request)
    {
        $this->validate($request,[
            'csv' => 'required',
            'splitter' => 'required|max:1|min:1'
        ]);

        $file = fopen($request->file('csv')->getRealPath(),'r');
        $jumlah = 0;
        while(!feof($file))
        {
            $row = fgetcsv($file,0,$request->splitter);
            $id_jurusan = null;
            if (Jurusan::checkName((isset($row[1])) ? $row[1] : ''))
                $id_jurusan = Jurusan::getIdByName($row[1]);
            Prodi::create([
                'nama' => $row[0],
                'id_jurusan' => $id_jurusan
            ]);
            $jumlah++;
        }
        fclose($file);

        return back()->with('message', 'Berhasil menambahkan '.$jumlah.' prodi!');
    }
}
