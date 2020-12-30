<?php

namespace PMW\Models;

use Illuminate\Database\Eloquent\Model;

class HakAksesPengguna extends Model
{

    protected $table = 'hak_akses_pengguna';

    public $timestamps = false;

    protected $fillable = [
        'id_pengguna', 'id_hak_akses', 'request_status'
    ];

}
