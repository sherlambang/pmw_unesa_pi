<?php

use Illuminate\Database\Seeder;
use PMW\Models\Proposal;
use PMW\Models\HakAkses;
use PMW\User;
use Faker\Factory;
use PMW\Models\Prodi;
use PMW\Models\Mahasiswa;
use PMW\Support\RequestStatus;
use PMW\Models\Jenis;

class ProposalSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $faker = Factory::create('id_ID');
        Jenis::create(['nama' => 'Barang']);
        Jenis::create(['nama' => 'Jasa']);
        Jenis::create(['nama' => 'Barang & Jasa']);

        $jumlahProposal = 73;

        $daftarDosenPembimbing = User::whereHas('hakAksesPengguna', function($query) {
            $query->where('nama', HakAkses::DOSEN_PEMBIMBING)
                ->where('status_request', RequestStatus::APPROVED);
        })->get();

        // Membuat proposal
        for($i = 1; $i <= $jumlahProposal; $i++ ) {
            // Membuat proposal
            $proposal = Proposal::create([
                'lolos' => false,
                'judul' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'direktori' => 'proposal.docx',
                'direktori_final' => null,
                'usulan_dana' => $faker->numberBetween(1000000, 5000000),
                'abstrak' => $faker->text($maxNbChars = 500),
                'keyword' => 'key|word',
                'jenis_id' => rand(1,3)
            ]);

            // Membuat 3 user sebagai tim
            for($j = 1; $j <= 3; $j++) {
                $user = User::create([
                    'nama' => $faker->name,
                    'id' => $faker->numerify('###########'),
                    'email' => $faker->email,
                    'alamat_asal' => $faker->address,
                    'alamat_tinggal' => $faker->address,
                    'no_telepon' => $faker->e164PhoneNumber,
                    'password' => bcrypt('secret'),
                    'request' => false,
                    'id_prodi' => Prodi::all()->pluck('id')->random()
                ]);

                // Menambahkan hak akses anggota
                $user->hakAksesPengguna()->attach(
                    HakAkses::where('nama', HakAkses::ANGGOTA)->first(), [
                        'status_request' => 'Approved'
                        ]
                );

                // Menjadikan user pertama sebagai ketua tim
                if($j == 1)
                    $user->jadikanKetua();

                // Menjadikan user sebagai mahasiswa
                $mahasiswa = Mahasiswa::create([
                    'id_pengguna' => $user->id,
                    'id_proposal' => $proposal->id,
                    'ipk' =>  $faker->biasedNumberBetween($min = 0, $max = 400)/100
                ]);
            }

            // Menambahkan dosen pembimbing pada proposal
            $proposal->tambahPembimbing(
                $daftarDosenPembimbing->random()
            );
        }
    }

}
