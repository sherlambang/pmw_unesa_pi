@extends('layouts.app')

@section('brand')
    Pengguna
@endsection

@push('css')
<link href="{{ asset('css/form.css') }}" rel="stylesheet"/>
@endpush

@section('content')
    <style>
        table tr:nth-child(even) {
            display: none;
        }
    </style>

    {{--alert--}}
    @if(session()->has('message'))
        <br>
        <div class="alert alert-info">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="btn-group">
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                {{ ucwords(str_replace('_',' ',$fakultas)) }}&nbsp;&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ route('daftar.pengguna',['fakultas' => 'semua_fakultas', 'role' => $role, 'perHalaman' => $perHalaman, 'q' => $q]) }}">Semua
                        Fakultas</a></li>
                @foreach($daftar_fakultas as $item)
                    <li>
                        <a href="{{ route('daftar.pengguna',[ 'fakultas' => str_replace(' ','_',strtolower($item->nama)), 'role' => $role, 'perHalaman' => $perHalaman, 'q' => $q]) }}">Fakultas {{ $item->nama }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                {{ ucwords(str_replace('_',' ',$role)) }}&nbsp;&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ route('daftar.pengguna',['fakultas' => $fakultas, 'role' => 'semua_hak_akses', 'perHalaman' => $perHalaman, 'q' => $q]) }}">Semua
                        Hak Akses</a>
                </li>
                @foreach($hak_akses as $item)
                    <li>
                        <a href="{{ route('daftar.pengguna',[ 'fakultas' => $fakultas, 'role' => str_replace(' ','_',strtolower($item->nama)), 'perHalaman' => $perHalaman, 'q' => $q]) }}">{{ $item->nama }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        @if($user->total() > 0)
            <a href="{{ route('unduh.filter.pengguna', ['fakultas' => $fakultas, 'role' => $role, 'q' => $q]) }}"
               class="btn btn-info">Unduh</a>
        @endif
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                {{ $perHalaman }} per halaman&nbsp;&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                @for($per = 5; $per <= $user->total(); $per += 5)
                    <li>
                        <a href="{{ route('daftar.pengguna',[ 'fakultas' => $fakultas, 'role' => $role, 'perHalaman' => $per, 'q' => $q]) }}">{{ $per }} data per halaman</a>
                    </li>
                @endfor
                    <li>
                        <a href="{{ route('daftar.pengguna',[ 'fakultas' => $fakultas, 'role' => $role, 'perHalaman' => $user->total(), 'q' => $q]) }}"> Semua data</a>
                    </li>
            </ul>
        </div>
    </div>

    <button class="btn btn-success" data-toggle="modal" data-target="#tambah">Tambah pengguna</button>

    <div id="tambah" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="card">
                <div class="card-header" data-background-color="blue">
                    <h4>Tambah pengguna</h4>
                </div>
                <div class="card-content">
                    <div class="row">
                        <form action="{{ route('tambah.user') }}" method="post" class="form-group" id="tambah-form">
                            <div class="col-sm-6">
                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                <label>NIP/NIM</label>
                                <input type="text" name="id" required class="form-control">
                                <label>Email</label>
                                <input type="email" name="email" required class="form-control">
                                <br>
                            </div>
                            <div class="col-sm-6">
                                <label>Hak akses</label><br>
                                <input type="checkbox" name="hakakses[]" value="Super Admin"> Super Admin<br>
                                <input type="checkbox" name="hakakses[]" value="Admin Universitas"> Admin
                                Universitas<br>
                                <input type="checkbox" name="hakakses[]" value="Admin Fakultas"> Admin Fakultas
                                <select name="idfakultas">
                                    @foreach($daftar_fakultas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select><br>
                                <input type="checkbox" name="hakakses[]" value="Reviewer"> Reviewer<br>
                                <input type="checkbox" name="hakakses[]" value="Dosen Pembimbing"> Dosen Pembimbing<br>
                                <input type="checkbox" name="hakakses[]" value="Anggota"> Mahasiswa<br>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group">
                        <a class="btn btn-success btn-round btn-sm" onclick="event.preventDefault(); document.getElementById('tambah-form').submit()">Tambah</a>
                        <button class="btn btn-danger btn-round btn-sm" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <form action="#" class="card cari">
                <input type="text" placeholder="Cari berdasarkan NIP/NIM/Nama . . ." value="{{ $q == '[]' ? '' : $q }}" id="cari">
                <button type="button" id="buttonsearch"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
    

    <div class="card">
        <div class="card-header" data-background-color="orange">
            <h4>Daftar pengguna PMW UNESA</h4>
            <p class="category">Jumlah pengguna sesuai filter adalah {{ $user->total() }}</p>
        </div>
        <div class="card-content">
            @if(count($user) == 0)
                <div class="alert alert-info">
                    <h4>Maaf, masih belum ada data sesuai filter!</h4>
                </div>
            @else
                <table class="table use-datatable">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIM/NIP</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($user as $item)
                        <tr>
                            <td>
                                {{ ($user->currentpage() * $user->perpage()) + (++$c) - $user->perpage()  }}
                            </td>
                            <td>{{ $item->id }}</td>
                            @if(is_null($item->nama))
                                <td>
                                    "Pengguna ini belum mengatur nama"
                                </td>
                            @else
                                <td>
                                    {{ $item->nama }}
                                </td>
                            @endif
                                <td>
                                    {{ (is_null($item->id_prodi)) ? '-' : \PMW\Models\Prodi::find($item->id_prodi)->nama }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-success btn-sm" onclick="event.preventDefault(); document.getElementById('simpan-{{ $item->id }}').submit()">Simpan</a>
                                        <a class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('hapus-{{ $item->id }}').submit()">Hapus</a>
                                        <a class="btn btn-primary btn-sm" onclick="$(this).parent().parent().parent().next().toggle()">Detail/Edit</a>
                                    </div>
                                </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="border-top: none !important;">
                                <div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4">
                                            <label>Hak akses</label>
                                            <form action="{{ route('tambah.hakaksespengguna') }}" method="post" id="simpan-{{ $item->id }}">
                                                {{ csrf_field() }}
                                                {{ method_field('put') }}
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                @foreach($hak_akses as $value)
                                                    <input type="checkbox" name="hakakses[]" value="{{ $value->nama }}"
                                                           @if(\PMW\User::find($item->id)->hasRole($value->nama)) checked
                                                           @endif>
                                                    {{ $value->nama }}
                                                    <br>
                                                @endforeach
                                            </form>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <label>E-mail</label>
                                            <p>{{ $item->email }}</p>
                                            <label>Alamat asal</label>
                                            <p>{{ (is_null($item->alamat_asal)) ? '-' : $item->alamat_asal }}</p>
                                            <label>Alamat tinggal</label>
                                            <p>{{ (is_null($item->alamat_tinggal)) ? '-' : $item->alamat_tinggal }}</p>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <label>No telepon</label>
                                            <p>{{ is_null($item->no_telepon) ? '-' : $item->no_telepon }}</p>
                                            <p>Pengguna ini mendaftar
                                                pada {{ Carbon\Carbon::createFromTimeStamp(strtotime($item->created_at))->diffForHumans() }}
                                                dan telah memperbarui
                                                profil {{ Carbon\Carbon::createFromTimeStamp(strtotime($item->updated_at))->diffForHumans() }}</p>
                                            <label>Reset password</label>
                                            <form action="{{ route('edit.pengguna.password') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <input type="password" name="password" class="form-control">
                                                <input type="submit" value="Reset" class="btn btn-warning btn-sm">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <form action="{{ route('hapus.pengguna') }}" method="post" id="hapus-{{ $item->id }}">
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <input type="hidden" name="id" value="{{ $item->id }}">
                        </form>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="card-footer">
            <div style="float: none;margin: 0 auto">
                {{ $user->links() }}
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $('#buttonsearch').click(function () {
        q = $('#cari').val()
        q = q == '' ? '[]' : q
        window.location = "{{ route('daftar.pengguna',[ 'fakultas' => $fakultas, 'role' => $role, 'perHalaman' => $perHalaman, 'q' => '']) }}/" + encodeURIComponent(q)
    })
    
    $('#cari').keypress(function(e){
        if(e.keyCode==13) {
            e.preventDefault();
            $('#buttonsearch').click();
        }
    })
</script>
@endpush