<?php

use Illuminate\Database\Seeder;
use PMW\Models\HakAkses;

class HakAksesSeeder extends Seeder
{

    private $hakakses = [
        'Anggota',
        'Ketua Tim',
        'Dosen Pembimbing',
        'Reviewer',
        'Admin Fakultas',
        'Admin Universitas',
        'Super Admin'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->hakakses as $hakakse){
            HakAkses::create([
                'nama' => $hakakse
            ]);
        }
    }
    
}
