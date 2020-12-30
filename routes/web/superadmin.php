<?php

Route::get('pengaturan/sistem', 'Page\SuperAdminController@pengaturan')->name('pengaturansistem');

Route::group(['prefix' => 'daftar'], function () {

    Route::get('pengguna/{fakultas}/{role}/{perHalaman}/{q}', [
        'uses' => 'Page\SuperAdminController@tampilDataPengguna',
        'as' => 'daftar.pengguna'
    ]);

    Route::get('fakultas', [
        'uses' => 'Page\SuperAdminController@tampilDataFakultas',
        'as' => 'daftar.fakultas'
    ]);

    Route::get('jurusan/{fakultas}/{perHalaman}', [
        'uses' => 'Page\SuperAdminController@tampilDataJurusan',
        'as' => 'daftar.jurusan'
    ]);

    Route::get('prodi/{jurusan}/{perHalaman}', [
        'uses' => 'Page\SuperAdminController@tampilDataProdi',
        'as' => 'daftar.prodi'
    ]);

    Route::get('proposal/{fakultas}/{lolos}/{period}/{perHalaman}', [
        'uses' => 'Page\SuperAdminController@tampilDataProposal',
        'as' => 'daftar.proposal'
    ]);

});

Route::group(['prefix' => 'permintaan'], function () {

    Route::get('hakakses', [
        'uses' => 'Page\SuperAdminController@tampilRequestHakAkses',
        'as' => 'permintaan.hakakses'
    ]);

});

Route::group(['prefix' => 'tambah'], function () {

    Route::put('fakultas', [
        'uses' => 'FakultasController@tambah',
        'as' => 'tambah.fakultas'
    ]);

    Route::put('jurusan', [
        'uses' => 'JurusanController@tambah',
        'as' => 'tambah.jurusan'
    ]);

    Route::put('prodi', [
        'uses' => 'ProdiController@tambah',
        'as' => 'tambah.prodi'
    ]);

    Route::put('pengguna', [
        'uses' => 'UserController@tambah',
        'as' => 'tambah.user'
    ]);

    Route::put('aspek', [
        'uses' => 'AspekController@tambah',
        'as' => 'tambah.aspek'
    ]);

    Route::put('hakaksespengguna', [
        'uses' => 'UserController@editHakAkses',
        'as' => 'tambah.hakaksespengguna'
    ]);

    Route::post('csv/jurusan', [
        'uses' => 'JurusanController@tambahCsv',
        'as' => 'tambah.csv.jurusan'
    ]);

    Route::post('csv/fakultas', [
        'uses' => 'FakultasController@tambahCsv',
        'as' => 'tambah.csv.fakultas'
    ]);

    Route::post('csv/prodi', [
        'uses' => 'ProdiController@tambahCsv',
        'as' => 'tambah.csv.prodi'
    ]);

    Route::post('jenis', [
        'uses' => 'JenisController@tambah',
        'as' => 'tambah.jenis'
    ]);

});

Route::group(['prefix' => 'hapus'], function () {
    Route::put('fakultas', [
        'uses' => 'FakultasController@hapus',
        'as' => 'hapus.fakultas'
    ]);

    Route::put('jurusan', [
        'uses' => 'JurusanController@hapus',
        'as' => 'hapus.jurusan'
    ]);

    Route::put('prodi', [
        'uses' => 'ProdiController@hapus',
        'as' => 'hapus.prodi'
    ]);

    Route::put('pengguna', [
        'uses' => 'UserController@hapus',
        'as' => 'hapus.pengguna'
    ]);

    Route::put('aspek', [
        'uses' => 'AspekController@hapus',
        'as' => 'hapus.aspek'
    ]);

    Route::post('jenis', [
        'uses' => 'JenisController@hapus',
        'as' => 'hapus.jenis'
    ]);
});

Route::group(['prefix' => 'edit'], function () {

    Route::put('fakultas', [
        'uses' => 'FakultasController@edit',
        'as' => 'edit.fakultas'
    ]);

    Route::put('jurusan', [
        'uses' => 'JurusanController@edit',
        'as' => 'edit.jurusan'
    ]);

    Route::put('prodi', [
        'uses' => 'ProdiController@edit',
        'as' => 'edit.prodi'
    ]);

    Route::put('aspek', [
        'uses' => 'AspekController@edit',
        'as' => 'edit.aspek'
    ]);

    Route::put('terimahakakses', [
        'uses' => 'HakAksesController@terimaRequest',
        'as' => 'set.terimahakakses'
    ]);

    Route::put('tolakhakakses', [
        'uses' => 'HakAksesController@tolakRequest',
        'as' => 'set.tolakhakakses'
    ]);

    Route::get('reviewer/{idproposal}', [
        'uses' => 'Page\SuperAdminController@editReviewer',
        'as' => 'edit.reviewer'
    ]);

    Route::patch('reviewer/{idproposal}', [
        'uses' => 'ReviewerController@kelola',
        'as' => 'edit.reviewer'
    ]);

    Route::post('pengaturan', [
        'uses' => 'PengaturanController@edit',
        'as' => 'edit.pengaturan'
    ]);

    Route::post('jenis', [
        'uses' => 'JenisController@edit',
        'as' => 'edit.jenis'
    ]);

    Route::post('pengguna/password', [
        'uses' => 'UserController@editPassword',
        'as' => 'edit.pengguna.password'
    ]);

});

Route::group(['prefix' => 'unduh'], function () {

    Route::get('proposal/{fakultas}/{lolos}/{period}', [
        'uses' => 'Page\SuperAdminController@unduhProposal',
        'as' => 'unduh.filter.proposal'
    ]);

    Route::get('pengguna/{fakultas}/{role}', [
        'uses' => 'Page\SuperAdminController@unduhPengguna',
        'as' => 'unduh.filter.pengguna'
    ]);

});