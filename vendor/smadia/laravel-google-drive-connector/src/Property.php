<?php

namespace Smadia\LaravelGoogleDrive;

use Illuminate\Support\Facades\Storage;

trait Property {

    /**
     * List of properties which accessible from outside of this class
     *
     * @var array
     */
    private $allowedPropertyToGet = [
        'name',
        'extension',
        'namex',
        'size',
        'timestamp',
        'mimetype',
        'path',
        'contents',
        'dirname',
        'basename'
    ];
    
    /**
     * List of property which can set from outside of this class
     *
     * @var array
     */
    private $allowedPropertyToSet = [
        'path'
    ];

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var string
     */
    private $namex;

    /**
     * @var string
     */
    private $size;

    /**
     * @var string|null
     */
    private $mimetype = null;

    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var string
     */
    private $dirname;

    /**
     * @var string
     */
    private $basename;

    /**
     * @var string
     */
    private $contents;

    /**
     * Define all properties from certain file or directory
     *
     * @return void
     */
    private function defineProperties()
    {
        $file = collect(Storage::cloud()->listContents('/', true));

        $file = $file->where('path', '=', $this->path)
                    ->first();

        if(!is_null($file)) {
            $this->exist = true;
            $this->namex = $file['filename'] . '.' . $file['extension'];
            $this->name = $file['filename'];
            $this->extension = $file['extension'];
            $this->size = $file['size'];
            $this->timestamp = $file['timestamp'];
            $this->dirname = $file['dirname'];
            $this->basename = $file['basename'];
            $this->type = $file['type'];

            if($this->type === 'file') {
                $this->mimetype = $file['mimetype'];
            }
        }
    }

    /**
     * Setter for file or directory's properties
     *
     * @param string $property
     * @param string|int $value
     */
    public function __set($property, $value)
    {
        if(in_array($property, $this->allowedPropertyToSet)) {
            $this->{$property} = $value;

            if($property === 'path') {
                $this->defineProperties();

                if($this->type === 'file') {
                    $this->contents = Storage::cloud()->get($this->path);
                }
            }
        }
    }

    /**
     * Get a value from certain property
     *
     * @param string $property
     * @return string|int|null
     */
    public function __get($property)
    {
        if(in_array($property, $this->allowedPropertyToGet)) {
            if($property === 'contents' && $this->type === 'dir')
                return null;
                
            return $this->{$property};
        }

        return null;
    }

    /**
     * Get all properties of current file or directory
     *
     * @return array
     */
    public function getAllProperties()
    {
        $properties = [];

        foreach($this->allowedPropertyToGet as $property) {
            $properties[$property] = $this->{$property};
        }

        return $properties;
    }

}