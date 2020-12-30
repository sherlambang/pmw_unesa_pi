<?php

Route::get('admin/fakultas/proposal/{lolos}/{period}/{perHalaman}', 'Page\AdminFakultasController@daftarProposal')->name('proposaladminfakultas');

Route::get('admin/fakultas/unduh/proposal/{period}/{lolos}', 'Page\AdminFakultasController@unduhProposal')->name('unduhproposalfakultas');