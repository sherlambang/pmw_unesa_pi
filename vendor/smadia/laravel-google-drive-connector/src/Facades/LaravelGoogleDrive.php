<?php

namespace Smadia\LaravelGoogleDrive\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelGoogleDrive extends Facade {

    public static function getFacadeAccessor()
    {
        return 'LGD';
    }

}