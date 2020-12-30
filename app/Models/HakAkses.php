<?php

namespace PMW\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HakAkses extends Model
{

    const KETUA_TIM = 'Ketua Tim';

    const ANGGOTA = 'Anggota';

    const REVIEWER = 'Reviewer';

    const ADMIN_FAKULTAS = 'Admin Fakultas';

    const ADMIN_UNIVERSITAS = 'Admin Universitas';

    const DOSEN_PEMBIMBING = 'Dosen Pembimbing';

    const SUPER_ADMIN = 'Super Admin';

    protected $table = 'hak_akses';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'nama'
    ];

    /**
     * Mendapatkan daftar pengguna dari hak akses tertentu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pengguna()
    {
        return $this->belongsToMany('PMW\User', 'hak_akses_pengguna', 'id_hak_akses', 'id_pengguna');
    }

    /**
     * Mendapatkan daftar pengguna yang meminta hak akses
     */
    public static function permintaanHakAkses()
    {
        return DB::table('pengguna')
            ->rightJoin(DB::raw('
                 (
                    SELECT
                     id_pengguna,
                     nama AS hakakses,
                     id_hak_akses
                    FROM hak_akses_pengguna
                     LEFT JOIN hak_akses ON hak_akses_pengguna.id_hak_akses = hak_akses.id
                    WHERE hak_akses_pengguna.status_request = \'Requesting\'
                 ) AS status
                '), function ($join){
                $join->on('status.id_pengguna', '=', 'pengguna.id');
            })
            ->select('id_pengguna', 'nama', 'hakakses', 'id_hak_akses')
            ->orderBy('nama')
            ->get();
    }
}
