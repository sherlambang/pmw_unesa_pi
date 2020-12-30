<?php

namespace PMW\Support\FileHandler;

use PMW\Contract\FileHandler;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LocalStorage implements FileHandler {

    /**
     * Menyimpan file yang telah diunggah ke storage
     *
     * @param string $dir
     * @param string $filename
     * @param string $contents
     * @return void
     */
    public function save($dir, $file)
    {
        $namaFile = Auth::user()->id . ' ' . Auth::user()->nama . '.' . $file->getClientOriginalExtension();
        $file->storePubliclyAs('public/' . $dir, $namaFile);

        return $namaFile;
    }

    /**
     * Menghapus file
     *
     * @param string $filepath
     * @return void
     */
    public function delete($filedir, $filename)
    {
        if(Storage::exists($filedir . '/' . $filename))
            Storage::delete($filedir . '/'. $filename );
    }

    /**
     * Menngunduh file
     *
     * @param string $filepath
     * @return void
     */
    public function download($filedir, $filename)
    {
        return response()->download(
            storage_path('app/public/' . $filedir . '/' . $filename)            
        );
    }
    
}