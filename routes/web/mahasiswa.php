<?php

Route::namespace('Page')->group(function() {

    Route::get('proposal', [
        'uses' => 'PageController@proposalDetail',
        'as' => 'proposal'
    ]);

    Route::get('logbook', [
        'uses' => 'MahasiswaController@logbook',
        'as' => 'logbook'
    ]);
    
});

Route::get('laporan', function(){
    return view('mahasiswa.laporan');
})->name('laporan');

Route::get('infotim', [
    'uses' => 'Page\MahasiswaController@infoTim',
    'as' => 'info.tim'
]);

Route::post('konfirmasitim', [
    'uses' => 'TeamController@konfirmasiTim',
    'as' => 'mahasiswa.konfirmasi.tim'
]);

Route::group(['prefix' => 'undang'], function () {

    Route::post('anggota', [
        'uses' => 'UndanganTimController@buatUndangan',
        'as' => 'undang.anggota',
    ]);

    Route::post('terima', [
        'uses' => 'UndanganTimController@terimaUndangan',
        'as' => 'terima.undangan.tim'
    ]);

    Route::post('tolak', [
        'uses' => 'UndanganTimController@tolakUndangan',
        'as' => 'tolak.undangan.tim'
    ]);

    Route::post('kirimulang', [
        'uses' => 'UndanganTimController@kirimUlang',
        'as' => 'kirimulang.undangan.tim'
    ]);

});

Route::group(['prefix' => 'hapus'],function(){

    Route::post('undangan',[
        'uses' => 'UndanganTimController@hapusUndangan',
        'as' => 'hapus.undangan.tim'
    ]);

});
