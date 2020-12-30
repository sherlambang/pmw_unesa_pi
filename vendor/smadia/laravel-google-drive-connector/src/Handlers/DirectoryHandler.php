<?php

namespace Smadia\LaravelGoogleDrive\Handlers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Smadia\LaravelGoogleDrive\Handlers\FileHandler;
use Smadia\LaravelGoogleDrive\Action;
use Smadia\LaravelGoogleDrive\Property;
use Smadia\LaravelGoogleDrive\ListContent;

class DirectoryHandler implements Action {

    use Property;

    /**
     * List contents of current directory
     *
     * @var Smadia\LaravelGoogleDrive\ListContent|null
     */
    private $listContents;

    private $exists = true;

    private $recursive = false;
    
    public function __construct($name = null)
    {
        $this->name = $name;
        
        $this->generateDirectoryPath();

        if ($this->isExist()) {
            $this->listContents = new ListContent($this->path);
        }
    }

    /**
     * This method will generate the path of requested directory name
     *
     * @return void
     */
    private function generateDirectoryPath()
    {
        // Get the list of requested directory
        // We will get the path of the director from the last dir of the array
        // e.g : the requested directory is home/image/group
        // we will explode until we have the group's path directory
        $listOfDirectory = explode('/', $this->name);

        $counter = 0;

        // Get the list contents of the root directory
        $__ls = collect(Storage::cloud()->listContents('/', $this->recursive));

        // Loop for generate the path
        while ($counter < count($listOfDirectory)) {
            if ($listOfDirectory[$counter] !== '') {
                $dir = $__ls->where('type', '=', 'dir')
                            ->where('filename', '=', $listOfDirectory[$counter])
                            ->first();

                if (is_null($dir)) {
                    $this->exists = false;
                    break;
                }

                $this->path = $dir['path'];
                
                $__ls = collect(Storage::cloud()->listContents($this->path, $this->recursive));
            }
            
            $counter++;
        }

        $this->defineProperties();
    }

    /**
     * Generate whether the directory is exist
     *
     * @return void
     */
    private function generateExist()
    {
        $ls = collect(Storage::cloud()->listContents('/', false));
        $dir = $ls->where('type', '=', 'dir');

        $this->exists = $dir->count() > 0;
    }

    /**
     * Put a new file to current directory
     *
     * @param string|FileHandler|UploadedFile $filename
     * @param FileHandler|null $contents
     *
     * @return void
     */
    public function put($filename, $contents = null)
    {
        $uploaded = false;
        if ($this->isExist()) {
            if (is_string($filename)) {
                // If the $filename is a string, then create a new file with
                // $filename as it's filename
                $this->putAsFile($filename, $contents);
                $uploaded = true;
                $fileNameWithExtension = $filename;
            } elseif ($filename instanceof FileHandler) {
                // If the $filename is a FileHandler instance, then create a new
                // file based on the FileHandler instance
                $this->putAsFileHandler($filename);
                $uploaded = true;
                $fileNameWithExtension = $filename->namex;
            } elseif ($filename instanceof UploadedFile) {
                $this->putAsUploadedFileRequest($filename);
                $uploaded = true;
                $fileNameWithExtension = $filename->getClientOriginalName();
            }

            if ($uploaded) {
                return $this->getFileHandlerRecentlyUploaded(
                    $fileNameWithExtension
                );
            }
        }

        return null;
    }

    /**
     * Put a new file base on the FileHandler class
     *
     * @param FileHandler $file
     *
     * @return void
     */
    private function putAsFileHandler(FileHandler $file)
    {
        Storage::cloud()->put(
            $this->path . '/' . $file->namex, $file->contents
        );

        $file = $this->ls(function ($filter) use ($file) {
                    $filter->where('name', '=', $file->name)
                            ->where('extension', '=', $file->extension);

                    return $filter;
        });
    }

    /**
     * Put a new file base on the requested filename and contents
     *
     * @param string $filename
     * @param string|int $contents
     *
     * @return void
     */
    private function putAsFile($filename, $contents)
    {
        Storage::cloud()->put(
            $this->path . '/' . $filename, $contents
        );
    }

    private function putAsUploadedFileRequest(UploadedFile $request)
    {
        $fileNameWithExtension = $request->getClientOriginalName();
        $contents = file_get_contents($request->getRealPath());

        Storage::cloud()->put(
            $this->path . '/' . $fileNameWithExtension, $contents
        );
    }

    /**
     * Get the FileHandler of recently uploaded file
     *
     * @param string $fileNameWithExtension
     *
     * @return void
     */
    private function getFileHandlerRecentlyUploaded($fileNameWithExtension)
    {
        $filename = substr($fileNameWithExtension, 0, strrpos($fileNameWithExtension, '.'));
        
        $extension = substr($fileNameWithExtension, strrpos($fileNameWithExtension, '.') + 1, strlen($fileNameWithExtension) - strrpos($fileNameWithExtension, '.') - 1);

        $lsOfCurrentDir = $this->ls(function ($filter) use ($filename, $extension) {
            return $filter->where('type', '=', 'file')
                    ->where('filename', '=', $filename)
                    ->where('extension', '=', $extension)
                    ->sortBy('timestamp');
        });

        $file = $lsOfCurrentDir->toCollect()->last();

        $fileHandler = new FileHandler();
        $fileHandler->path = $file['path'];

        return $fileHandler;
    }

    /**
     * Check whether the directory is exists
     *
     * @return boolean
     */
    public function isExist()
    {
        return $this->exists;
    }

    /**
     * Rename the current directory
     *
     * @param string $newname
     *
     * @return void
     */
    public function rename($newname)
    {
        if ($this->isExist()) {
            Storage::cloud()->move($this->path, $this->dirname . '/' . $newname);
        }
    }

    /**
     * Delete the directory
     *
     * @return boolean
     */
    public function delete()
    {
        if ($this->isExist()) {
            Storage::cloud()->deleteDirectory($this->path);
            return true;
        }

        return false;
    }

    /**
     * Go to spesific directory inside the current directory
     *
     * @param string $dirname
     * @param int $index
     *
     * @return void
     */
    public function dir($dirname, $index = 0)
    {
        $directory = $this->listContents->dir($dirname, $index);

        return $directory;
    }

    /**
     * Get the list content of requested directory
     *
     * @param mixed $filter
     *
     * @return Smadia\LaravelGoogleDrive\ListContent|null
     */
    public function ls($filter = null)
    {
        if ($this->isExist()) {
            return $this->listContents->filter($filter);
        }

        return null;
    }

    /**
     * Get a spesific file
     *
     * @param string $filename
     * @param string $extension
     * @param int $index
     *
     * @return void
     */
    public function file($filename, $extension, $index = 0)
    {
        return $this->listContents->file($filename, $extension, $index);
    }

    /**
     * Make a new directory inside the current directory
     *
     * @param string $dirname
     *
     * @return void
     */
    public function mkdir($dirname)
    {
        $name = $this->path . '/' . $dirname;
        $create = Storage::cloud()->makeDirectory($this->path . '/' . $dirname);

        $directory = new DirectoryHandler();
        $directory->path = $name;

        return $directory;
    }

    /**
     * Get the parent directory of current directory
     *
     * @return void
     */
    public function parent()
    {
        $directoryHandler = new DirectoryHandler();
        $directoryHandler->path = $this->dirname;

        return $directoryHandler;
    }
}
