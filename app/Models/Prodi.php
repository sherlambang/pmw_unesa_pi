<?php

namespace PMW\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_jurusan',
        'nama'
    ];

    /**
     * Mendapatkan jurusan dari prodi tertentu
     *
     * @return mixed
     */
    public function jurusan()
    {
        return $this->belongsTo('PMW\Models\Jurusan','id_jurusan')->first();
    }

    /**
     * Mendapatkan daftar pengguna atau pengguna secara spesifik
     *
     * @param null $pengguna
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|mixed
     */
    public function pengguna($pengguna = null)
    {
        if(!is_null($pengguna))
            return $this->hasMany('PMW\User')->where('id_pengguna',$pengguna)->first();

        return $this->hasMany('PMW\User');
    }
}
