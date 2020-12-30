<?php

/**
 * Route buat testing
 */

Route::get('tes',function(){
    Excel::create('tes',function($excel){
        $excel->sheet('Sheet',function($sheet){
            $sheet->setOrientation('landscape');
            $sheet->setAutoSize(false);
            $sheet->appendRow([
                'Nama','NIM','Nama Tim'
            ]);
        });
    })->export('xls');
});

Route::get('user',function(){
    return \Illuminate\Support\facades\Auth::user()->mahasiswa()->undanganTimAnggota()->first();
    // return \Illuminate\Support\facades\Auth::user()->mahasiswa()->first();
});

Route::get('bla',function(){
//    foreach (\PMW\Models\Proposal::find(20)->pengguna()->cursor() as $pengguna)
//    {
//        echo $pengguna->nama . '<br/>';
//    }
return \PMW\User::find('5817875802')->proposal()->first()->pivot;
});

Route::get('upload',function(){
    return view('bagas.upload');
});

Route::post('upload','LaporanAkhirController@unggah')->name('upload');

Route::get('getfile',function(){
    return response()->download(storage_path('app/public/tes.xls'));
});

Route::get('radiobutton',function (){
    return view('bagas.radio');
});

Route::post('radiobutton',function (){
    return request()->has('a.1') ? 'ya' : 'tidak';
})->name('radio');