<?php

namespace PMW\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_fakultas',
        'nama'
    ];

    /**
     * Mendapatkan daftar prodi atau prodi tertentu dari jurusan tertentu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prodi($prodi = null)
    {
        if (!is_null($prodi))
            return $this->hasMany('PMW\Models\Prodi')->where('nama',$prodi)->first();

        return $this->hasMany('PMW\Models\Prodi');
    }

    /**
     * Mendapatkan fakultas dari jurusan tertentu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fakultas()
    {
        return $this->belongsTo('PMW\Models\Fakultas', 'id_fakultas')->first();
    }

    public static function checkName($nama){
        if (Jurusan::where('nama', $nama)->count() > 0)
            return true;
        return false;
    }

    public static function getIdByName($nama)
    {
        return Jurusan::where('nama',$nama)->first()->id;
    }
}
