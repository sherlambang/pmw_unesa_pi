<?php

/**
 * Route ini diperuntukkan bagi user dengan hak akses ketua tim
 */

Route::group(['prefix' => 'unggah'], function () {

    Route::get('proposal', [
        'uses' => 'Page\KetuaController@unggahProposal',
        'as' => 'unggah.proposal'
    ]);

    Route::patch('proposal', [
        'uses' => 'ProposalController@unggah',
        'as' => 'unggah.proposal'
    ]);

    Route::get('proposal/final', [
        'uses' => 'Page\KetuaController@unggahProposalFinal',
        'as' => 'unggah.proposal.final'
    ]);
    
    Route::put('proposal/final', [
        'uses' => 'ProposalFinalController@unggah',
        'as' => 'unggah.proposal.final'
    ]);

    Route::post('laporan/kemajuan',function (){
        return view('mahasiswa.unggahlaporan');
    })->name('unggah.laporan.kemjuan');

    Route::put('laporan/kemajuan', [
        'uses' => 'LaporanController@unggah',
        'as' => 'unggah.laporan.kemajuan'
    ]);

    Route::get('laporan/akhir',function (){
        return view('mahasiswa.unggahlaporanakhir');
    })->name('unggah.laporanakhir');

    Route::put('laporan/akhir', [
        'uses' => 'LaporanAkhirController@unggah',
        'as' => 'unggah.laporan.akhir'
    ]);
    
    Route::put('laporan/magang', [
        'uses' => 'LaporanMagangController@unggah',
        'as' => 'unggah.laporan.magang'
    ]);
    
    Route::put('laporan/keuangan', [
        'uses' => 'LaporanKeuanganController@unggah',
        'as' => 'unggah.laporan.keuangan'
    ]);

    Route::put('logbook', [
        'uses' => 'LogBookController@tambah',
        'as' => 'tambah.logbook'
    ]);

});

Route::group(['prefix' => 'edit'], function () {

    Route::get('proposal', [
        'uses' => 'Page\KetuaController@editProposal',
        'as' => 'edit.proposal'
    ]);

    Route::patch('proposal', [
        'uses' => 'ProposalController@edit',
        'as' => 'edit.proposal'
    ]);

    Route::get('proposal/final', [
        'uses' => 'Page\KetuaController@editProposalFinal',
        'as' => 'edit.proposal.final'
    ]);

    Route::get('logbook/{id}', [
        'uses' => 'Page\KetuaController@editLogbook',
        'as' => 'halaman.edit.logbook'
    ]);

    Route::patch('logbook', [
        'uses' => 'LogBookController@edit',
        'as' => 'edit.logbook'
    ]);

});

Route::group(['prefix' => 'undang'], function () {

    Route::post('dosen', [
        'uses' => 'UndanganDosenController@kirimUndangan',
        'as' => 'undang.pembimbing'
    ]);

    Route::post('dosen/kirimulang', [
        'uses' => 'UndanganDosenController@kirimUlang',
        'as' => 'kirimulang.undangan.pembimbing'
    ]);

    Route::post('dosen/hapus', [
        'uses' => 'UndanganDosenController@hapus',
        'as' => 'hapus.undangan.pembimbing'
    ]);

});

Route::group(['prefix' => 'cari'], function(){

    Route::post('dosen', [
        'uses' => 'UserController@cariPembimbing',
        'as' => 'cari.pembimbing'
    ]);

});

Route::group(['prefix' => 'tambah'],function (){

    Route::put('logbook',[
        'uses' => 'LogBookController@tambah',
        'as' => 'tambah.logbook'
    ]);

});

Route::group(['prefix' => 'hapus'], function(){

    Route::delete('logbook', [
        'uses' => 'LogBookController@hapus',
        'as' => 'hapus.logbook'
    ]);

    Route::delete('anggota',[
        'uses' => 'TeamController@hapusAnggota',
        'as' => 'hapus.anggota'
    ]);

});
