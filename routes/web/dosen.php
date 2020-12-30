<?php

Route::group(['prefix' => 'terima'],function (){

    Route::post('undangan',[
        'uses' => 'UndanganDosenController@terimaUndangan',
        'as' => 'terima.undangan.dosen'
    ]);

});

Route::post('tolak/undangan', [
    'uses' => 'UndanganDosenController@tolak',
    'as' => 'tolak.undangan.dosen'
]);

Route::get('bimbingan',[
    'uses' => 'Page\DosenController@bimbingan',
    'as' => 'bimbingan'
]);
