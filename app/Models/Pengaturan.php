<?php

namespace PMW\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    public $table = 'pengaturan';

    protected $fillable = [
        'id',
        'nama',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    /**
     * Mendapatkan nilai minimum proposal untuk dinyatakan lolos
     *
     * @return mixed
     */
    public static function nilaiMinimumProposal()
    {
        return static::where(
            'nama',
            'Nilai minimum proposal')
            ->first()
            ->keterangan;
    }

    /**
     * Mendaptkan waktu batas pengunggahan proposal
     *
     * @return mixed
     */
    public static function batasUnggahProposal()
    {
        return static::where(
            'nama',
            'Batas pengumpulan proposal')
            ->first()
            ->keterangan;
    }

    /**
     * Mendaptkan waktu batas pengunggahan proposal final
     *
     * @return mixed
     */
    public static function batasUnggahProposalFinal()
    {
        return static::where(
            'nama',
            'Batas pengumpulan proposal final')
            ->first()
            ->keterangan;
    }

    /**
     * Mendapatkan waktu batas penilaian tahap tertentu
     *
     * @param $tahap
     * @return mixed
     */
    public static function batasPenilaian($tahap)
    {
        return static::where(
            'nama',
            'Batas penilaian proposal tahap ' . $tahap)
            ->first()
            ->keterangan;
    }

    /**
     * Mengecek apakah waktu sekarang telah melewati waktu
     * batas pengunggahan proposal
     *
     * @return bool
     */
    public static function melewatiBatasUnggahProposal()
    {
        return (Carbon::now()->diffInSeconds(
                Carbon::parse(static::batasUnggahProposal()), false
            ) <= 0);
    }
    
    /**
     * Mengecek apakah waktu sekarang telah melewati waktu
     * batas pengunggahan proposal final
     *
     * @return bool
     */
    public static function melewatiBatasUnggahProposalFinal()
    {
        return (Carbon::now()->diffInSeconds(
                Carbon::parse(static::batasUnggahProposalFinal()), false
            ) <= 0);
    }

    /**
     * Mengecek apakah waktu sekarang telah melewati waktu
     * batas penilaian tahap tertentu
     *
     * @param $tahap
     * @return bool
     */
    public static function melewatiBatasPenilaian($tahap)
    {
        return (Carbon::now()->diffInSeconds(
                Carbon::parse(static::batasPenilaian($tahap)
                ), false) <= 0);
    }

    public static function checkName($name)
    {
        if (Pengaturan::where('nama', $name)->count() > 0)
            return true;
        return false;
    }

    public static function getByName($name)
    {
        return Pengaturan::where('nama', $name)->first();
    }

}
