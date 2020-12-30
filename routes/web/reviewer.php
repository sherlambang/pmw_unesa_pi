<?php

Route::group(['prefix' => 'tambah'], function () {

    Route::get('review/{id}', [
        'uses' => 'Page\ReviewerController@tambahReview',
        'as' => 'tambah.review'
    ]);

    Route::put('review/{id}', [
        'uses' => 'ReviewController@tambah',
        'as' => 'tambah.nilai.review'
    ]);

});

Route::group(['prefix' => 'daftar', 'namespace' => 'Page'], function () {

    Route::get('proposal/reviewer/{tahap?}', [
        'uses' => 'ReviewerController@daftarProposal',
        'as' => 'daftar.proposal.reviewer'
    ]);

    Route::get('proposal/final', [
        'uses' => 'ReviewerController@daftarProposalFinal',
        'as' => 'daftar.proposal.final'
    ]);

    Route::get('laporan/kemajuan', [
        'uses' => 'ReviewerController@daftarLaporanKemajuan',
        'as' => 'daftar.laporan.kemajuan'
    ]);

    Route::get('laporan/akhir', [
        'uses' => 'ReviewerController@daftarLaporanAkhir',
        'as' => 'daftar.laporan.akhir'
    ]);

});

Route::group(['prefix' => 'lihat'], function () {

    Route::get('nilai/review/{id}', [
        'uses' => 'Page\ReviewerController@lihatNilaiReview',
        'as' => 'lihat.nilai.review'
    ]);

});

Route::group(['prefix' => 'edit'], function () {

    Route::get('nilai/review/{id}', [
        'uses' => 'Page\ReviewerController@editNilaiReview',
        'as' => 'edit.nilai.review'
    ]);

    Route::patch('nilai/review/{id}', [
        'uses' => 'ReviewController@edit',
        'as' => 'edit.nilai.review'
    ]);

});
