<?php

Route::get('admin/universitas/proposal/{fakultas}/{lolos}/{period}/{perHalaman}', 'Page\AdminUniversitasController@daftarProposal')->name('proposaladminuniv');

Route::get('admin/universitas/unduh/proposal/{fakultas}/{period}/{lolos}', 'Page\AdminUniversitasController@unduhProposal')->name('unduhproposaluniv');