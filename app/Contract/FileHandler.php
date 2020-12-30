<?php

namespace PMW\Contract;

/**
 * Contract untuk meng-handle storage
 */
interface FileHandler {

    /**
     * Menyimpan file yang telah diunggah ke storage
     *
     * @param string $dir
     * @param string $contents
     * @return void
     */
    public function save($dir, $file);

    /**
     * Menghapus file
     *
     * @param string $filepath
     * @return void
     */
    public function delete($filedir, $filename);

    /**
     * Menngunduh file
     *
     * @param string $filepath
     * @return void
     */
    public function download($filedir, $filename);

}