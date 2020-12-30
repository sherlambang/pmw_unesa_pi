<?php

use Illuminate\Database\Seeder;
use PMW\Models\Aspek;

class AspekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //aspek pemasaran
        Aspek::create([
            'nama' => 'Perencanaan produk'
        ]);
        Aspek::create([
            'nama' => 'Penetapan harga'
        ]);
        Aspek::create([
            'nama' => 'System distribusi'
        ]);
        Aspek::create([
            'nama' => 'Kegiatan promosi'
        ]);
        Aspek::create([
            'nama' => 'Pasar'
        ]);
        Aspek::create([
            'nama' => 'Persaingan'
        ]);
        Aspek::create([
            'nama' => 'Relasi jaringan'
        ]);

        //aspek manajemen dan organisasi
        Aspek::create([
            'nama' => 'Merekap pengeluaran dan pemasukan usaha'
        ]);
        Aspek::create([
            'nama' => 'Memonitor mobilitas produksi'
        ]);
        Aspek::create([
            'nama' => 'Pelaporan'
        ]);

        //aspek produksi
        Aspek::create([
            'nama' => 'Proses produksi dan jasa'
        ]);
        Aspek::create([
            'nama' => 'Mesin dan peralatan yang dibutuhkan'
        ]);
        Aspek::create([
            'nama' => 'Bahan baku dan bahan embantu yang dibutuhkan'
        ]);
        Aspek::create([
            'nama' => 'Tenaga produksi'
        ]);
    }
}
