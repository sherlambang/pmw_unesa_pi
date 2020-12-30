<?php

namespace PMW\Support;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PMW\Models\Fakultas;
use PMW\Models\Jenis;
use PMW\Models\Jurusan;
use PMW\Models\Prodi;
use PMW\User;
use PMW\Models\Proposal;
use PMW\Facades\Dana;

class ExcelExport
{

    protected $name;

    protected $totalSheet;

    public function daftarUser()
    {
        Excel::create('tes', function ($excel) {
            $excel->sheet('Sheet', function ($sheet) {
                $sheet->setOrientation('landscape');
                // $sheet->setAutoSize(false);
                $sheet->appendRow([
                    'Nama', 'NIM'
                ]);
                foreach (User::all() as $user) {
                    $sheet->appendRow([
                        $user->nama, $user->id
                    ]);
                }
            });
        })->export('xls');
    }

    public function unduhDaftarPengguna($fakultas, $role)
    {
        $GLOBALS['fakultas'] = $fakultas;
        $GLOBALS['role'] = $role;
        return Excel::create('Daftar Pengguna', function ($excel) {
            $excel->sheet('Sheet', function ($sheet) {
                $pengguna = ($GLOBALS['fakultas'] == 'Semua Fakultas') ? User::orderBy('nama')->get() : User::perFakultas($GLOBALS['fakultas']);
                $sheet->setOrientation('landscape');
                $sheet->setAutoSize(true);
                $sheet->appendRow([
                    'No',
                    'NIP/NIM',
                    'Nama',
                    'Fakultas',
                    'Jurusan',
                    'Prodi',
                    'No Telepon',
                    'Alamat Asal',
                    'Alamat Tinggal',
                    'Hak Akses'
                ]);

                function writeRow($value, $counter)
                {
                    $hak_akses = '';
                    foreach (User::find($value->id)->hakAksesPengguna()->get() as $item){
                        $hak_akses = $hak_akses.$item->nama.', ';
                    }
                    return [
                        $counter,
                        $value->id,
                        $value->nama,
                        Fakultas::find(Jurusan::find(Prodi::find($value->id_prodi)['id_jurusan'])['id_fakultas'])['nama'],
                        Jurusan::find(Prodi::find($value->id_prodi)['id_jurusan'])['nama'],
                        Prodi::find($value->id_prodi)['nama'],
                        $value->no_telepon,
                        $value->alamat_asal,
                        $value->alamat_tinggal,
                        rtrim($hak_akses, ', ')
                    ];
                }
                $counter = 0;
                foreach ($pengguna as $value) {
                    if ($GLOBALS['role'] != 'semua_hak_akses'){
                        if ($value->hasRole(ucwords(str_replace('_',' ', $GLOBALS['role'])))){
                            $sheet->appendRow(writeRow($value, ++$counter));
                        }
                    }
                    else {
                        $sheet->appendRow(writeRow($value, ++$counter));
                    }
                }
            });
        })->export('xls');
    }

    public function unduhProposal($fakultas = null, $tahap = 'semua', $period = 'semua')
    {
        $GLOBALS['tahap'] = $tahap;
        $GLOBALS['fakultas'] = $fakultas;
        $nama_file = (is_null($fakultas)) ? 'Proposal Semua Fakultas' : 'Proposal Fakultas ' . Fakultas::find($fakultas)->nama;
        return Excel::create($nama_file, function ($excel) use ($period) {
            $excel->sheet('Sheet', function ($sheet) use ($period) {
                $proposal = (is_null($GLOBALS['fakultas'])) ? Proposal::all() : Proposal::proposalPerFakultas($GLOBALS['fakultas']);
                if ($period != 'semua'){
                    $proposal = $proposal->filter(function ($value, $key) use ($period){
                        return Carbon::parse($value->created_at)->year == $period;
                    });
                }
                $proposal = $proposal->filter(function ($value, $key) {
                    return !is_null($value->jenis_id);
                });
                $sheet->setOrientation('landscape');
                $sheet->setAutoSize(true);
                $sheet->mergeCells('A1:B1');
                $sheet->appendRow([
                    (is_null($GLOBALS['fakultas'])) ? 'Proposal Semua Fakultas' : 'Proposal Fakultas ' . Fakultas::find($GLOBALS['fakultas'])->nama
                ]);
                $sheet->appendRow(['']);
                $sheet->appendRow([
                    'No',
					'Id',
                    'NIM Ketua',
                    'Nama Ketua',
                    'Fakultas',
                    'Jurusan',
                    'Prodi',
                    'No Telepon',
                    'Alamat Asal',
                    'Alamat Tinggal',
                    'NIM Anggota 1',
                    'Nama Anggota 1',
                    'NIM Anggota 2',
                    'Nama Anggota 2',
                    'Judul',
                    'Jenis Usaha',
                    'Usulan Dana',
                    'NIP Dosen Pembimbing',
                    'Nama Dosen Pembimbing',
                    'Reviewer Tahap 1',
                    'Nilai Tahap 1',
                    'Reviewer Tahap 2',
                    'Nilai Tahap 2',
                    'Detil Nilai Tahap 1',
                    'Detil Nilai Tahap 2'
                ]);
                $counter = 0;
                foreach ($proposal as $item) {
                    $prp = Proposal::find($item->id);
                    $reviewerTahap1 = '';
                    $reviewerTahap2 = '';
                    if ($GLOBALS['tahap'] != 'semua') {
                        if ($prp->lolos(explode('_', $GLOBALS['tahap'])[1])) {
                            $ketua = $prp->ketua();
                            foreach ($prp->reviewer()->wherePivot('tahap', 1)->get() as $value) {
                                $reviewerTahap1 = $reviewerTahap1 . $value->nama . ', ';
                            }
                            foreach ($prp->reviewer()->wherePivot('tahap', 2)->get() as $value) {
                                $reviewerTahap2 = $reviewerTahap2 . $value->nama . ', ';
                            }
                            $anggota = $prp->mahasiswa()->whereNotIn('id_pengguna', [$prp->ketua()->id])->get();
                            $n1 = '';
                            if (!is_null($prp->daftarReview(1))){
                                foreach($prp->daftarReview(1)->get() as $i){
                                $n1 = $n1.$i->penilaian()->get()->sum('pivot.nilai').' ';
                            }
                            }
                            
                            $n2 = '';
                            if (!is_null($prp->daftarReview(2))){
                                foreach($prp->daftarReview(2)->get() as $i){
                                $n2 = $n2.$i->penilaian()->get()->sum('pivot.nilai').' ';
                            }
                            }
                            
                            $sheet->appendRow([
                                ++$counter,
								$prp->id,
                                $ketua->id,
                                $ketua->nama,
                                $ketua->prodi()->jurusan()->fakultas()->nama,
                                $ketua->prodi()->jurusan()->nama,
                                $ketua->prodi()->nama,
                                $ketua->no_telepon,
                                $ketua->alamat_asal,
                                $ketua->alamat_tinggal,
                                (isset($anggota[0])) ? $anggota[0]->pengguna()->id : '-',
                                (isset($anggota[0])) ? $anggota[0]->pengguna()->nama : '-',
                                (isset($anggota[1])) ? $anggota[1]->pengguna()->id : '-',
                                (isset($anggota[1])) ? $anggota[1]->pengguna()->nama : '-',
                                $item->judul,
                                Jenis::find($item->jenis_id)->nama,
                                Dana::format($item->usulan_dana),
                                (is_null($prp->pembimbing()) ? '-' : $prp->pembimbing()->id),
							    (is_null($prp->pembimbing()) ? '-' : $prp->pembimbing()->nama),
                                rtrim($reviewerTahap1, ','),
                                $prp->nilaiRataRata(1),
                                rtrim($reviewerTahap2, ','),
                                $prp->nilaiRataRata(2),
                                $n1,
                                $n2
                            ]);
                        }
                    } else {
                        foreach ($prp->reviewer()->wherePivot('tahap', 1)->get() as $value) {
                            $reviewerTahap1 = $reviewerTahap1 . $value->nama . ', ';
                        }
                        foreach ($prp->reviewer()->wherePivot('tahap', 2)->get() as $value) {
                            $reviewerTahap2 = $reviewerTahap2 . $value->nama . ', ';
                        }
                        $n1 = '';
                        if (!is_null($prp->daftarReview(1))){
                            foreach($prp->daftarReview(1)->get() as $i){
                                $n1 = $n1.$i->penilaian()->get()->sum('pivot.nilai').' ';
                            }
                        }
                            
                            $n2 = '';
                            if (!is_null($prp->daftarReview(2))){
                                foreach($prp->daftarReview(2)->get() as $i){
                                $n2 = $n2.$i->penilaian()->get()->sum('pivot.nilai').' ';
                            }
                            }
                            
                        $ketua = $prp->ketua();
                        $anggota = $prp->mahasiswa()->whereNotIn('id_pengguna', [$prp->ketua()->id])->get();
                        $sheet->appendRow([
                            ++$counter,
							$prp->id,
                            $ketua->id,
                            $ketua->nama,
                            $ketua->prodi()->jurusan()->fakultas()->nama,
                            $ketua->prodi()->jurusan()->nama,
                            $ketua->prodi()->nama,
                            $ketua->no_telepon,
                            $ketua->alamat_asal,
                            $ketua->alamat_tinggal,
                            (isset($anggota[0])) ? $anggota[0]->pengguna()->id : '-',
                            (isset($anggota[0])) ? $anggota[0]->pengguna()->nama : '-',
                            (isset($anggota[1])) ? $anggota[1]->pengguna()->id : '-',
                            (isset($anggota[1])) ? $anggota[1]->pengguna()->nama : '-',
                            $item->judul,
                            Jenis::find($item->jenis_id)->nama,
                            Dana::format($item->usulan_dana),
                            (is_null($prp->pembimbing()) ? '-' : $prp->pembimbing()->id),
							(is_null($prp->pembimbing()) ? '-' : $prp->pembimbing()->nama),
                            rtrim($reviewerTahap1, ','),
                            $prp->nilaiRataRata(1),
                            rtrim($reviewerTahap2, ','),
                            $prp->nilaiRataRata(2),
                            $n1,
                            $n2
                        ]);
                    }
                }
            });
        })->export('xls');
    }

    public function name($name)
    {
        $this->name = $name;
    }

    public function sheet($sheet)
    {
        $this->totalSheet = $sheet;
    }

    public function export()
    {
        Excel::create('tes', function ($excel) {
            $excel->sheet('Sheet', function ($sheet) {
                $sheet->setOrientation('landscape');
                $sheet->setAutoSize(false);
                $sheet->appendRow([
                    'Nama', 'NIM', 'Nama Tim'
                ]);
            });
        })->export('xls');
    }

}