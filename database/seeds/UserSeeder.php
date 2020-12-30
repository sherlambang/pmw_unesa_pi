<?php

use Illuminate\Database\Seeder;
use PMW\User;
use PMW\Models\HakAkses;
use PMW\Support\RequestStatus;
use Faker\Factory;
use PMW\Models\Prodi;
use PMW\Models\Mahasiswa;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create('id_ID');
        for($i = 1; $i <= 100; $i++) {
            // Membuat user
            $user = User::create([
                'id' => $faker->numerify('###########'),
                'nama' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('secret'),
                'request' => true,
                'id_prodi' => Prodi::all()->pluck('id')->random()
            ]);

            // Mengeset hak akses
            $user->hakAksesPengguna()->attach(HakAkses::all()->random(), [
                'status_request' => RequestStatus::APPROVED
            ]);

            // Jika hak akses adalah mahasiswa, maka
            // menambah pada tabel mahasiswa
            if($user->isMahasiswa()) {
                $user->request = false;
                $user->save();

                Mahasiswa::create([
                    'id_pengguna' => $user->id,
                    'ipk' => Factory::create()->biasedNumberBetween($min = 0, $max = 400)/100
                ]);
            }
        }
    }

}
