<?php

namespace PMW\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    public $table = 'penilaian';

    protected $fillable = [
        'id_review',
        'id_aspek',
        'nilai',
        'created_at',
        'updated_at'
    ];

}
