<?php

/**
 * Route untuk testing
 */
Route::get('tesRafy',function (){
    return \PMW\Models\Proposal::find(3)->ketua();
});