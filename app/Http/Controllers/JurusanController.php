<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use PMW\Models\Fakultas;
use PMW\Models\Jurusan;

class JurusanController extends Controller
{
    public function tambah(Request $request)
    {
        foreach (explode(PHP_EOL, $request->nama) as $item){
            Jurusan::create([
                'nama' => $item
            ]);
        }

        return back()->with('message', 'Berhasil menambahkan '.count(explode(PHP_EOL, $request->nama)).' jurusan!');
    }

    public function tambahCsv(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required',
            'splitter' => 'required|max:1|min:1'
        ]);

        $file = fopen($request->file('csv')->getRealPath(),'r');
        $jumlah = 0;
        while(!feof($file))
        {
            $row = fgetcsv($file,0,$request->splitter);
            $id_fakultas = null;
            if (Fakultas::checkName((isset($row[1])) ? $row[1] : ''))
                $id_fakultas = Fakultas::getIdByName($row[1]);
            Jurusan::create([
                'nama' => $row[0],
                'id_fakultas' => $id_fakultas
            ]);
            $jumlah++;
        }
        fclose($file);

        return back()->with('message', 'Berhasil menambahkan '.$jumlah.' jurusan!');
    }

    public function hapus(Request $request)
    {
        $jurusan = Jurusan::find($request->id);
        $nama = $jurusan->nama;
        $jurusan->delete();

        return back()->with('message', 'Berhasil menghapus '.$nama.'!');
    }

    public function edit(Request $request)
    {
        $data = Jurusan::find($request->id);
        $data->nama = $request->nama;
        $data->id_fakultas = $request->id_fakultas;
        $data->save();

        return back()->with('message', 'Berhasil mengedit ');
    }

    public function daftarBerdasarkanFakultas(Request $request)
    {
        $jurusan = Jurusan::where('id_fakultas', $request->fakultas)->get();

        return response()->json($jurusan);
    }
}
