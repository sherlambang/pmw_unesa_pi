<?php

namespace PMW\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jenis extends Model
{
    use SoftDeletes;

    protected $table = 'jenis';

    public $timestamps = false;

    protected $fillable = [
        'nama'
    ];

    /**
     * mendapatkan proposal
     *
     * @param null $queryReturns
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proposal($queryReturns = null)
    {
        $data = $this->hasMany('PMW\Models\Proposal', 'jenis_id');
        return $queryReturns ? $data : $data->get();
    }

}
