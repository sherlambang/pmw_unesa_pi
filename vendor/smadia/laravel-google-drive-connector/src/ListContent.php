<?php

namespace Smadia\LaravelGoogleDrive;

use Illuminate\Support\Facades\Storage;
use Smadia\LaravelGoogleDrive\Handlers\DirectoryHandler;
use Smadia\LaravelGoogleDrive\Handlers\FileHandler;

class ListContent {

    private $path;

    private $listContents;

    private $filter = null;

    private $recursive = false;

    public function __construct($path)
    {
        $this->path = $path;

        $this->generateListContents();
    }

    /**
     * Generate the list contents of current path
     *
     * @return void
     */
    private function generateListContents()
    {
        // If the directory is exists, then, get all subdirectories and files
        $ls = collect(Storage::cloud()->listContents($this->path, $this->recursive));
        
        if(is_callable($this->filter)) {
            $ls = ($this->filter)($ls);
        }
        
        $this->listContents = $ls;
    }
    
    /**
     * Filters the list content
     *
     * @param mixed $filter
     * 
     * @return void
     */
    public function filter($filter)
    {
        $this->filter = $filter;

        $this->generateListContents();

        return $this;
    }

    /**
     * Get specific deirectory
     *
     * @param string $dirname
     * @param int $index
     * 
     * @return FileHandler
     */
    public function dir($dirname, $index = 0)
    {
        $dirs = $this->listContents->where('type', '=', 'dir')
                    ->where('filename', '=', $dirname);

        if($index >= $dirs->count())
            die('No such directory !');

        $dir = $dirs[$dirs->keys()[$index]];

        $fileHandler = new DirectoryHandler();
        $fileHandler->path = $dir['path'];

        return $fileHandler;
    }

    /**
     * Get the spesific file in list contents
     *
     * @param string $filename
     * @param string $extension
     * @param int $index
     * 
     * @return void
     */
    public function file($filename, $extension, $index = 0)
    {
        $file = $this->listContents->where('type', '=', 'file')
                    ->where('filename', '=', $filename)
                    ->where('extension', '=', $extension);

        if($index >= $file->count())
            die('No file !');

        $file = $file[$file->keys()[$index]];

        $fileHandler = new FileHandler();
        $fileHandler->path = $file['path'];

        return $fileHandler;
    }
    
    /**
     * Return list contents as an array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->listContents->toArray();
    }

    /**
     * Return list contents as Laravel's collection
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function toCollect()
    {
        return $this->listContents;
    }

    /**
     * Return list contents as an array with object as it's member
     *
     * @return array
     */
    public function toObject()
    {
        $collect = [];

        foreach($this->listContents as $contents) {
            if($contents['type'] === 'dir') {
                $dirObject = new DirectoryHandler();
                $dirObject->path = $contents['path'];
                array_push($collect, $dirObject);
            } else if( $contents['type'] === 'file') {
                $fileObject = new FileHandler();
                $fileObject->path = $contents['path'];
                array_push($collect, $fileObject);
            }
        }

        return collect($collect);
    }

}
