<?php

namespace PMW\Models;

use Illuminate\Database\Eloquent\Model;

class LogBook extends Model
{
    protected $table = 'logbook';

    protected $fillable = [
        'id_proposal',
        'catatan',
        'biaya',
        'created_at',
        'updates_at',
        'tanggal',
        'direktori_foto',
        'lat_foto',
        'long_foto'
    ];

    /**
     * Mendapatkan proposal dari logbook tertentu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proposal()
    {
        return $this->belongsTo('PMW\Models\Proposal','id_proposal')->first();
    }
}
