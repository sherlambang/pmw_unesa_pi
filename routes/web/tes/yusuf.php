<?php

/*
 * Route testing buat yusuf
 */

Route::get('/loginz', function(){
    return view('auth');
})->name('loginz');

Route::get('/utama', function(){
    return view('main');
})->name('utama');

Route::get('/cari', function(){
    return view('mahasiswa/carimahasiswa');
})->name('cari');

