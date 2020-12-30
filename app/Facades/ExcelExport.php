<?php

namespace PMW\Facades;

use Illuminate\Support\Facades\Facade;
use PMW\Models\Proposal;

class ExcelExport extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'ExcelExport';
    }

}