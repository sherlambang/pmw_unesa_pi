@extends('layouts.app')

@section('brand')
    Prodi
@endsection

@section('content')
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
                {{ ($jurusan == 'semua_jurusan') ? '' : 'Jurusan' }}
                {{ ucwords(str_replace('_',' ',$jurusan)) }}&nbsp;&nbsp;
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="{{ route('daftar.prodi', ['jurusan' => 'semua_jurusan', 'perHalaman' => $perHalaman]) }}">Semua
                        Jurusan</a></li>
                @foreach($daftarjurusan as $item)
                    <li>
                        <a href="{{ route('daftar.prodi', ['jurusan' => str_replace(' ','_',strtolower($item->nama)), 'perHalaman' => $perHalaman]) }}">
                            Fakultas {{ (is_null($item->id_fakultas) ? 'belum diatur' : $item->fakultas()->nama) }}
                            <br>
                            Jurusan {{ $item->nama }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                {{ $perHalaman }} per halaman&nbsp;&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                @for($per = 5; $per <= $prodi->total(); $per += 5)
                    <li>
                        <a href="{{ route('daftar.prodi', ['jurusan' => $jurusan, 'perHalaman' => $per]) }}">{{ $per }}
                            data per halaman</a></li>
                @endfor
                <li>
                <li>
                    <a href="{{ route('daftar.prodi', ['jurusan' => $jurusan, 'perHalaman' => $prodi->total()]) }}">Semua
                        Data</a></li>
                </li>
            </ul>
        </div>
    </div>

    <a class="btn btn-success" data-toggle="collapse" data-target="#tambah">Tambah prodi</a>
    <div id="tambah" class="collapse" role="dialog">
        <div class="card">
            <div class="card-header" data-background-color="blue">
                <h4 class="title">Tambah prodi</h4>
            </div>
            <div class="card-content">
                <div class="row">
                    <div class="col-md-6">
                        <p>Tambahkan secara manual</p>
                        <form action="{{ route('tambah.prodi') }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <textarea name="nama" placeholder="Pisahkan dengan enter untuk menambahkan banyak prodi"
                                      class="form-control" rows="7" minlength="1" required></textarea>
                            <button class="btn btn-success btn-sm" type="submit">Tambah</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <p>Tambahkan dengan .csv file dengan format <b>nama_prodi</b>[splitter]<b>nama_jurusan</b>(opsional)
                        </p>
                        <form action="{{ route('tambah.csv.prodi') }}" method="post"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group label-floating">
                                <label>Splitter</label>
                                <input class="form-control" type="text" name="splitter" maxlength="1" minlength="1" required>
                            </div>
                            <label>Pilih file .csv</label>
                            <input name="csv" type="file" accept=".csv" required><br>
                            <button class="btn btn-success btn-sm" type="submit">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" data-background-color="purple">
            <h4 class="title">Prodi</h4>
            <p class="category">Jumlah prodi sesui filter adalah {{ $prodi->total() }} prodi</p>
        </div>
        <div class="card-content table-responsive">
            <table class="table">
                <thead class="text-primary">
                <tr>
                    <th>No</th>
                    <th>Nama dan Jurusan</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($prodi as $item)
                    <tr>
                        <td>{{ ($prodi->currentpage() * $prodi->perpage()) + (++$no) - $prodi->perpage()  }}</td>
                        <td>
                            <form action="{{ route('edit.prodi') }}" method="post" class="col-xs-11" id="simpan-{{ $item->id }}">
                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <span class="material-input"></span>
                                <div class="input-group">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" name="nama" value="{{ $item->nama }}"
                                                   class="form-control" style="border: 1px">
                                        </div>
                                        <div class="col-md-7">
                                            <select name="id_jurusan" class="form-control">
                                                @if(is_null($item->id_jurusan))
                                                    <option value="">Pilih Jurusan</option>
                                                    @foreach($daftarjurusan as $value)
                                                        <option value="{{ $value->id }}">Jurusan {{ $value->nama }} |
                                                            Fakultas {{ (is_null($value->id_fakultas)) ? 'belum diatur' : $value->fakultas()->nama }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="{{ $item->id_jurusan }}">
                                                        Jurusan {{ $item->jurusan()->nama }} |
                                                        Fakultas
                                                        @if(is_null($item->jurusan()->id_fakultas))
                                                            belum diatur
                                                        @else
                                                            {{ $item->jurusan()->fakultas()->nama }}
                                                        @endif
                                                    </option>
                                                    @foreach($daftarjurusan as $value)
                                                        <option value="{{ $value->id }}">Jurusan {{ $value->nama }} |
                                                            Fakultas {{ (is_null($value->id_fakultas)) ? 'belum diatur' : $value->fakultas()->nama }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('hapus.prodi') }}" method="post" id="hapus-{{ $item->id }}">
                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                <input type="hidden" name="id" value="{{ $item->id }}">
                            </form>
                            <div class="btn-group">
                                <button class="btn btn-success btn-sm btn-round" onclick="event.preventDefault(); $('#simpan-{{ $item->id }}').submit()">Simpan</button>
                                <button class="btn btn-danger btn-sm btn-round" onclick="event.preventDefault(); $('#hapus-{{ $item->id }}').submit()">Hapus</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $prodi->links() }}
        </div>
    </div>
@endsection