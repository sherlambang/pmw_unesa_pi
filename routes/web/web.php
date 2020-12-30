<?php

/**
 * Ketika membuka halaman awal,
 * maka user akan diarahkan ke halaman dashboard
 */

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route::get('/test', function () {
//     $proposal = PMW\Models\Review::whereHas('proposal')->get();
//     echo $proposal->count();
        
//     // PMW\Models\Proposal->delete($proposal->toArray());
//     //     // foreach ($proposal as $entry) {
//     //     //         $entry->delete();
//     //     //     }
// });

//Route::post('/test', 'ProposalController@fetchTest');

Route::group(['middleware' => 'auth'], function () {

    Route::group(['middleware' => 'profil'], function () {

        /**
         * Jika user mmebuka halaman dashboard, maka akan
         * dilakukan pengecekan apakah user sedang login atau belum.
         * Jika belum, maka akan diarahkan ke halaman login.
         */
        Route::get('dashboard', [
            'uses' => 'Page\DashboardController@index',
            'as' => 'dashboard'
        ]);

        Route::group(['prefix' => 'detail'], function (){
            Route::get('proposal/{id}', [
                'uses'  => 'Page\PageController@detailProposal',
                'as'    => 'detail.proposal'
            ]);
            Route::get('logbook/{logbook}', [
                'uses' => 'Page\PageController@detailLogbook',
                'as' => 'detail.logbook'
            ]);
        });

        Route::group(['prefix' => 'cari'], function () {

            Route::get('carimahasiswa', [
                'uses' => 'UserController@cariMahasiswa',
                'as' => 'cari.mahasiswa'
            ]);

        });

        Route::get('logbook/{proposal}', [
            'uses' => 'Page\PageController@lihatLogbook',
            'as' => 'lihat.logbook'
        ]);


        Route::group(['prefix' => 'unduh'], function () {

            Route::post('proposal', [
                'uses' => 'ProposalController@unduh',
                'as' => 'unduh.proposal',
            ]);

            Route::post('proposal/final', [
                'uses' => 'ProposalFinalController@unduh',
                'as' => 'unduh.proposal.final'
            ]);

            Route::post('laporan/kemajuan', [
                'uses' => 'LaporanController@unduh',
                'as' => 'unduh.laporan.kemajuan',
            ]);

            Route::post('laporan/akhir', [
                'uses' => 'LaporanAkhirController@unduh',
                'as' => 'unduh.laporan.akhir',
            ]);

            Route::post('laporan/magang', [
                'uses' => 'LaporanMagangController@unduh',
                'as' => 'unduh.laporan.magang',
            ]);

            Route::post('laporan/keuangan', [
                'uses' => 'LaporanKeuanganController@unduh',
                'as' => 'unduh.laporan.keuangan',
            ]);

        });

        Route::get('data/proposal', [
            'uses' => 'ProposalController@dataAjax',
            'as' => 'data.proposal.ajax'
        ]);

        Route::group(['prefix' => 'lihat'], function () {

            Route::get('proposal/{id}', [
                'uses' => 'Page\PageController@proposalDetail',
                'as' => 'lihat.proposal'
            ]);

            Route::get('review/{id}', [
                'uses' => 'Page\PageController@lihatHasilReview',
                'as' => 'lihat.hasil.review'
            ]);

        });

        Route::post('request/pembimbing', [
            'uses' => 'HakAksesController@requestDosenPembimbing',
            'as' => 'request.pembimbing'
        ]);

        Route::post('request/reviewer', [
            'uses' => 'HakAksesController@requestReviewer',
            'as' => 'request.reviewer'
        ]);

    });

    Route::get('pengaturan', [
        'uses' => 'Page\PageController@pengaturan',
        'as' => 'pengaturan'
    ]);

    Route::prefix('daftar')->group(function (){

        Route::post('jurusan', [
            'uses' => 'JurusanController@daftarBerdasarkanFakultas',
            'as' => 'daftar.jurusan.fakultas'
        ]);

        Route::post('prodi', [
            'uses' => 'ProdiController@daftarBerdasarkanJurusan',
            'as' => 'daftar.prodi.jurusan'
        ]);

    });

    Route::group(['prefix' => 'ubah'], function () {

        Route::patch('password', [
            'uses' => 'UserController@gantiPassword',
            'as' => 'ubah.password'
        ]);

        Route::post('profil', [
            'uses' => 'UserController@editProfil',
            'as' => 'ubah.profil'
        ]);

    });

});

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');

Route::post('reset/password', [
    'uses' => 'UserController@resetPassword',
    'as' => 'reset.password'
]);