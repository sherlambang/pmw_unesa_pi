<?php

namespace PMW\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';

    protected $fillable = [
        'id',
        'id_pengguna',
        'id_proposal',
        'tahap',
        'komentar',
        'created_at',
        'updated_at'
    ];

    /**
     * Mendapatkan daftar penilaian dari review terkait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function penilaian()
    {
        return $this->belongsToMany('PMW\Models\Aspek', 'penilaian', 'id_review', 'id_aspek')->withPivot('nilai')->withTrashed();
    }

    /**
     * Mengubah nilai sesuai aspek tertentu
     *
     * @param string $aspek
     * @param int $nilai
     * @return void
     */
    public function ubahNilai($aspek, $nilai)
    {
        $this->penilaian()->detach($aspek);
        $this->penilaian()->attach($aspek, [
            'nilai' => $nilai
        ]);
    }
    
    public function proposal()
    {
        return $this->belongsTo('PMW\Models\Proposal', 'id_proposal', 'id');
    }

}