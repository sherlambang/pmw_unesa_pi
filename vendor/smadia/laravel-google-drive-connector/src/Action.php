<?php

namespace Smadia\LaravelGoogleDrive;

interface Action {

    /**
     * Rename the current working file or directory
     *
     * @param string $new
     * @return void
     */
    public function rename($newname);

    /**
     * Delete the current file or directory
     *
     * @return void
     */
    public function delete();

}