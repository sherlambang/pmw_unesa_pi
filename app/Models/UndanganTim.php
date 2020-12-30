<?php

namespace PMW\Models;

use Illuminate\Database\Eloquent\Model;

class UndanganTim extends Model
{
    public $table = 'undangan_tim';

    public $timestamps = false;

    protected $fillable = [
        'id_ketua',
        'id_anggota',
        'ditolak'
    ];
}
