<?php

namespace Smadia\LaravelGoogleDrive;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Smadia\LaravelGoogleDrive\Handlers\DirectoryHandler;
use Smadia\LaravelGoogleDrive\Handlers\FileHandler;

class LaravelGoogleDrive {

    private $rootDirectory = '/';

    /**
     * @var DirectoryHandler
     */
    private $directoryHandler;

    public function __construct()
    {
        $this->directoryHandler = new DirectoryHandler();
        $this->directoryHandler->path = $this->rootDirectory;
    }

    /**
     * Make a new directory
     *
     * @param string $dirname
     * 
     * @return DirectoryHandler
     */
    public function mkdir($dirname)
    {
        Storage::cloud()->makeDirectory($dirname);

        return new DirectoryHandler($dirname);
    }

    /**
     * Go to specified directory
     *
     * @param string $dirname
     * @param int $index
     * 
     * @return void
     */
    public function dir($dirname, $index = 0)
    {
        return new DirectoryHandler($dirname, $index);
    }

    /**
     * Create a new file
     *
     * @param UploadedFile|string $nameWithExtension
     * @param string|null $fileContents
     * 
     * @return FileHandler|null
     */
    public function put($nameWithExtension, $fileContents = null)
    {
        if($nameWithExtension instanceof UploadedFile) {
            $file = $nameWithExtension;
        } else {
            $file = new FileHandler(
                $nameWithExtension, $fileContents
            );
        }

        return $this->directoryHandler->put($file);
    }

    /**
     * Get the list contents of root directory
     *
     * @param mixed $filter
     * 
     * @return void
     */
    public function ls($filter = null)
    {
        return $this->directoryHandler->ls($filter);
    }

    /**
     * Get specified filename from root directory
     *
     * @param string $fileName
     * @param mixed $filter
     * 
     * @return void
     */
    public function file($fileName, $extension, $index = 0)
    {
        return $this->directoryHandler->file($fileName, $extension, $index);
    }

}
