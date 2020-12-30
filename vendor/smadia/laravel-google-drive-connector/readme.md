# Laravel Google Drive Connector
0.0.2

Installation
======

Make sure you have already installed laravel >= 5.2.*

Then, install package via composer 

<code>
composer require "smadia/laravel-google-drive-connector:0.0.*"
</code>

Register Facade in <code>config/app.php</code>

<pre>
'aliases' => [
    .....
    'LGD' => Smadia\LaravelGoogleDrive\Facades\LaravelGoogleDrive::class
    .....
]
</pre>

Register ServiceProvider in <code>config/app.php</code>

<pre>
'providers' => [
    .....
    Smadia\LaravelGoogleDrive\Providers\LaravelGoogleDriveServiceProvider::class
    .....
]
</pre>

You can create your own Facade and ServiceProvider manually, and then register it.

Configuration
=============

Before using package, make sure you have added new disk in <code>config/filesystem.php</code>

<pre>
'disks' => [

    // ...

    'googledrive' => [
        'driver' => 'google',
        'clientId' => env('GOOGLE_DRIVE_CLIENT_ID'),
        'clientSecret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
        'refreshToken' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
        'folderId' => env('GOOGLE_DRIVE_FOLDER_ID'),
    ],

    // ...

],
</pre>

Then, you should add new field in <code>.env</code> files
<pre>
FILESYSTEM_CLOUD=googledrive
GOOGLE_DRIVE_CLIENT_ID=xxx.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=xxx
GOOGLE_DRIVE_REFRESH_TOKEN=xxx
GOOGLE_DRIVE_FOLDER_ID=null
</pre>

How do I get the Google Drive API ? Follow these links :

- [Getting your Client ID and Secret](https://github.com/ivanvermeyen/laravel-google-drive-demo/blob/master/README/1-getting-your-dlient-id-and-secret.md)
- [Getting your Refresh Token](https://github.com/ivanvermeyen/laravel-google-drive-demo/blob/master/README/2-getting-your-refresh-token.md)
- [Getting your Root Folder ID](https://github.com/ivanvermeyen/laravel-google-drive-demo/blob/master/README/3-getting-your-root-folder-id.md)

## Usage

### Usage in non-object file

You can use LGD Facade in non-object file, such as routes/web.php, blade file. Below is the example usage in non-object file

<pre>
LGD::dir('mydir')->ls()->toObject();
</pre>

If you are using the Facade in object context, you should use the Facade in <code>Smadia\LaravelGoogleDrive\Facades\LaravelGoogleDrive</code> after the <code>namespace</code> declaration like below

<pre>
&lt;?php

namespace App\User;

// other use declaration
use Smadia\LaravelGoogleDrive\Facades\LaravelGoogleDrive;
// other use declaration
</pre>

After that, you can use LaravelGoogleDrive facade instead than LGD like below

<pre>
LaravelGoogleDrive::ls()->toArray()
</pre>

To use it only with LGD keyword, you can use <code>as</code> in use declaration like code below

<pre>
&lt;?php

namespace App\User;

// other use declaration
use Smadia\LaravelGoogleDrive\Facades\LaravelGoogleDrive as LGD;
// other use declaration
</pre>

### Get list contents from your root directory
<pre>
LGD::ls()->toArray()
</pre>

### Get the spesific directory
<pre>
LGD::dir('mydir')->ls()->toArray()
</pre>

### Get the spesific index if you have two or more with the same name

Index is started from 0 (zero)

<pre>
LGD::dir('mydir, 2)->ls()->toArray()
</pre>

### Get the spesific file in certain directory

<pre>
LGD::dir('mydir')->file('youfile', 'txt')->name
</pre>

### You can add index to get the file (if you have two or more files with the same name)

<pre>
LGD::dir('mydir')->file('yourfile', 'txt', 2)->name
</pre>

### Get other file's properties

Below is the list of file's property which you can get by adding <code>->(property)</code> after <code>file()</code> method

- name
- extension
- path
- dirname
- basename
- size
- timestamp
- mimetype (only for file type)
- type

### Filter

To filter the list contents of directory, you can use filter() method

<pre>
    LGD::ls(function($ls) {
        return $ls->where('type', '=', 'dir'); // another option of 'type' is 'file'
    })->toArray();
</pre>

### File Storing

These are some example to store file in Google Drive

<pre>
LGD::put('hello.txt', 'Hello World !');
</pre>

If you want store the uploaded file in your controller, you can use this one

<pre>
LGD::put('hello.txt', file_get_contents($request->file('file')->getRealPath()));
</pre>

If you want the name is same with the orginal uploaded file, you can assign the <code>$request->file()</code> as parameter

<pre>
LGD::put($request->file('file'));
</pre>

Directory methods
=====

Below is optional method to your directory
<table>
    <thead>
        <tr>
            <th>Method</th>
            <th>Usage</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>ls($filter = null)</code></td>
            <td>Get the list contents of the current directory</td>
            <td>This method will return the Smadia/GoogleDriveLaravel/ListContent Class. $filter is a closure which return the $filter itself</td>
        </tr>
        <tr>
            <td><code>delete()</code></td>
            <td>Delete the current directory</td>
            <td>-</td>
        </tr>
        <tr>
            <td><code>rename($newname)</code></td>
            <td>Rename the current directory</td>
            <td>This method will return back the DirectoryHandler Class</td>
        </tr>
        <tr>
            <td><code>put($filename, $contents = null)</code></td>
            <td>Put a new file to the current directory</td>
            <td>$contents can be null if the $filename is instance of FileHandler class. This method will return the FileHandler class of the created file. $filename can assign with UploadedFile class ($request->file() in Laravel)</td>
        </tr>
        <tr>
            <td><code>file($filename, $extension, $index = 0)</code></td>
            <td>Get the specified file</td>
            <td>This method will return the FileHandler class</td>
        </tr>
        <tr>
            <td><code>isExist()</code></td>
            <td>Check is the directory exist</td>
            <td>Return boolean type</td>
        </tr>
        <tr>
            <td><code>mkdir($dirname)</code></td>
            <td>Make a new directory inside the current directory</td>
            <td>This method will return the DirectoryHandler class of the created directory</td>
        </tr>
        <tr>
            <td><code>dir($dirname, $index = 0)</code></td>
            <td>Go to specified directory</td>
            <td>This method will return the DirectoryHandler class of the requested directory with certain index (default is 0)</td>
        </tr>
        <tr>
            <td><code>parent()</code></td>
            <td>Get the parent directory</td>
            <td>This method will return the DirectoryHandler class</td>
        </tr>
    </tbody>
</table>

File Methods
=====

<table>
    <thead>
        <tr>
            <th>Method</th>
            <th>Usage</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>rename($newname)</code></td>
            <td>Rename the current file</td>
            <td>This method will return the same FileHandler class</td>
        </tr>
        <tr>
            <td><code>delete()</code></td>
            <td>Delete the current file</td>
            <td>This method will not delete all files with the same name in the same level of directory</td>
        </tr>
        <tr>
            <td><code>isExist()</code></td>
            <td>Check whether the file is exist</td>
            <td>-</td>
        </tr>
        <tr>
            <td><code>getDir()</code></td>
            <td>Get the directory where the file is located</td>
            <td>This method will return the DirectoryHandler class</td>
        </tr>
        <tr>
            <td><code>show()</code></td>
            <td>Show the file in browser. Only work for jpeg, jpg, png, gif, and svg file extension</td>
            <td>-</td>
        </tr>
        <tr>
            <td><code>download()</code></td>
            <td>Give a download response</td>
            <td>-</td>
        </tr>
    </tbody>
</table>

List Content Methods
=====

<table>
    <thead>
        <tr>
            <th>Method</th>
            <th>Usage</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>toArray()</code></td>
            <td>Get list contents as array</td>
            <td>-</td>
        </tr>
        <tr>
            <td><code>toCollect()</code></td>
            <td>Get list contents as Laravel's collection</td>
            <td>-</td>
        </tr>
        <tr>
            <td><code>toObject()</code></td>
            <td>Get list contents as array which contains FileHandler or DirectoryHandler as it's member</td>
            <td>-</td>
        </tr>
        <tr>
            <td><code>dir($dirname, $index = 0)</code></td>
            <td>Get the directory where the file is located</td>
            <td>This method will return the DirectoryHandler class</td>
        </tr>
        <tr>
            <td><code>file($filename, $extension, $index = 0)</code></td>
            <td>Get the specific file name and extension </td>
            <td>This method will return the FileHandler class</td>
        </tr>
        <tr>
            <td><code>filter($filter)</code></td>
            <td>Get the directory where the file is located</td>
            <td>This method will return the DirectoryHandler class. $filter is a closure which return the $filter itself</td>
        </tr>
        <tr>
            <td><code>getAllProperties()</code></td>
            <td>Get all properties of a file or directory</td>
            <td>This method will return all properties in array</td>
        </tr>
    </tbody>
</table>