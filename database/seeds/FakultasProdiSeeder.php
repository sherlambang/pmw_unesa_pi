<?php

use Illuminate\Database\Seeder;
use PMW\Models\Fakultas;
use PMW\Models\Jurusan;
use PMW\Models\Prodi;

class FakultasProdiSeeder extends Seeder
{

    const PRODI = [
        'Ilmu Pendidikan' => [
            'Bimbingan & Konseling' => [
                'S1 Bimbingan Konseling'
            ],
            'Pendidikan Luar Biasa' => [
                'S1 Pendidikan Luar Biasa'
            ],
            'Pendidikan Luar Sekolah' => [
                'S1 Pendidikan Luar Sekolah'
            ],
            'Manajemen Pendidikan' => [
                'S1 Manajemen Pendidikan'
            ],
            'PG -PAUD' => [
                'S1 PG - PAUD'
            ],
            'Psikologi' => [
                'S1 Psikologi'
            ],
            'Teknologi Pendidikan' => [
                'S1 Teknologi Pendidikan'
            ],
            'Pendidikan Guru Sekolah Dasar' => [
                'S1 Pendidikan Guru Sekolah Dasar'
            ]
        ],

        'Bahasa & Seni' => [
            'Bahasa & Sastra Indonesia' => [
                'S1 Pendidikan Bahasa Indonesia',
                'S1 Sastra Indonesia'
            ],
            'Bahasa & Sastra Inggris' => [
                'S1 Pendidikan Bahasa Inggris',
                'S1 Sastra Inggris',
                'D3 Bahasa Inggris'
            ],
            'Bahasa & Sastra Daerah' => [
                'S1 Pendidikan Bahasa Jawa',
                'S1 Sastra Jawa'
            ],
            'Bahasa & Sastra Jerman' => [
                'S1 Pendidikan Bahasa Jerman',
                'S1 Sastra Jerman'
            ],
            'Bahasa & Sastra Jepang' => [
                'S1 Pendidikan Bahasa Jepang',
            ],
            'Bahasa & Sastra Mandarin' => [
                'S1 Pendidikan Bahasa Mandarin'
            ],
            'Seni Rupa' => [
                'S1 Pendidikan Seni Rupa',
                'S1 Seni Rupa'
            ],
            'Desain' => [
                'S1 Desain Komunikasi Visual',
                'D3 Desain Grafis'
            ],
            'Seni Drama, Tari & Musik' => [
                'S1 Pendidikan Seni Drama, Tari, & Musik',
                'S1 Seni Musik',
            ],
        ],

        'Matematika & Ilmu Pengetahuan Alam' => [
            'Matematika' => [
                'S1 Matematika',
                'S1 Pendidikan Matematika'
            ],
            'Fisika' => [
                'S1 Fisika',
                'S1 Pendidikan Fisika'
            ],
            'Kimia' => [
                'S1 Pendidikan Kimia',
                'S1 Kimia'
            ],
            'Biologi' => [
                'S1 Biologi',
                'S1 Pendidikan Biologi'
            ],
            'Pendidikan IPA' => [
                'S1 Pendidikan IPA',
            ],
        ],

        'Ilmu Sosial & Hukum' => [
            'PMP-Kn' => [
                'S1 Pendidikan Pancasila & Kewarganegaraan'
            ],
            'Administrasi Publik' => [
                'S1 Ilmu Administrasi Negara',
                'D3 Administrasi Negara'
            ],
            'Hukum' => [
                'S1 Ilmu Hukum'
            ],
            'Pendidikan Geografi' => [
                'S1 Pendidikan Geografi',
                'S1 Pendidikan IPS'
            ],
            'Pendidikan Sejarah' => [
                'S1 Pendidikan Sejarah',
            ],
            'Ilmu Sosial' => [
                'S1 Sosiologi',
                'S1 Ilmu komunikasi'
            ],
        ],

        'Teknik' => [
            'Teknik Informatika' => [
                'S1 Teknik Informatika',
                'S1 Sistem Informasi',
                'S1 Pendidikan Teknologi Informasi',
                'D3 Manajemen Informatika'
            ],
            'Teknik Mesin' => [
                'S1 Teknik Mesin',
                'D3 Teknik Mesin',
                'S1 Pend Teknik Mesin'
            ],
            'Teknik Elektro' => [
                'S1 Teknik Elektro',
                'S1 Pend. Teknik Elektro',
                'D3 Teknik Listrik',
            ],
            'Teknik Sipil' => [
                'S1 Teknik Sipil',
                'S1 Pend Teknik Bangunan',
                'D3 Teknik Sipil',
                'D3 Manajemen Transportasi'
            ],
            'Pendidikan Kesejahteraan Keluarga' => [
                'S1 Pendidikan Tata Busana',
                'S1 Pendidikan Kesejahteraan Keluarga',
                'S1 Pendidikan Tata Rias',
                'S1 Pendidikan Tata Boga',
                'D3 Tata Boga',
                'D3 Tata Busana'
            ]
        ],

        'Ilmu Keolahragaan' => [
            'Ilmu Keolahragaan' => [
                'S1 Pendidikan Kepelatihan Olahraga',
                'S1 Ilmu Keolahragaan',
                'S1 Pend. Jasmani, Kesehatan, & Rekreasi'
            ]
        ],

        'Ekonomi' => [
            'Pendidikan Ekonomi' => [
                'S1 Pendidikan Ekonomi',
                'S1 Pendidikan Administrasi Perkantoran',
                'S1 Pendidikan Akuntansi',
                'S1 Pendidikan Tata Niaga',
            ],
            'Manajemen' => [
                'S1 Manajemen',
            ],
            'Akutansi' => [
                'S1 Akuntansi',
                'D3 Akuntansi'
            ],
            'Ilmu Ekonomi' => [
                'S1 Ekonomi Islam'
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (static::PRODI as $fakultas => $daftarJurusan)
        {
            $idfakultas = Fakultas::create([
                'nama' => $fakultas
            ]);

            foreach ($daftarJurusan as $jurusan => $daftarProdi)
            {
                $idJurusan = Jurusan::create([
                    'nama'          => $jurusan,
                    'id_fakultas'   => $idfakultas->id
                ]);

                foreach ($daftarProdi as $prodi)
                {
                    Prodi::create([
                        'nama'      => $prodi,
                        'id_jurusan'=> $idJurusan->id
                    ]);
                }
            }
        }
    }

}
