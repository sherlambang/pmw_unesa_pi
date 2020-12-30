<?php

namespace PMW\Support\FileHandler;

use PMW\Contract\FileHandler;
use Smadia\LaravelGoogleDrive\Facades\LaravelGoogleDrive as LGD;
use Illuminate\Support\Facades\Auth;

class GoogleDrive implements FileHandler {

    /**
     * Menyimpan file yang telah diunggah ke storage
     *
     * @param string $dir
     * @param string $contents
     * @return string
     */
    public function save($dir, $file)
    {
        $namaFile = '2020 ' . Auth::user()->id . ' ' . Auth::user()->nama . '.' . $file->getClientOriginalExtension();
        LGD::dir($dir)->put($namaFile, file_get_contents($file->getRealPath()));

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
        $ekstensi = pathinfo($filename, PATHINFO_EXTENSION);
        $namafile = str_replace('.' . $ekstensi, '', $filename);
        LGD::dir($filedir)->file($namafile, $ekstensi)->delete();
    }

    /**
     * Menngunduh file
     *
     * @param string $filepath
     * @return void
     */
    public function download($filedir, $filename)
    {
        $ekstensi = pathinfo($filename, PATHINFO_EXTENSION);
        $namafile = str_replace('.' . $ekstensi, '', $filename);
        return LGD::dir($filedir)->file($namafile, $ekstensi)->download();
    }

}