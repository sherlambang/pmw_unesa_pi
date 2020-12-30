@extends('layouts.app')

@section('brand')
    Jurusan
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
                {{ ($fakultas == 'semua_fakultas') ? '' : 'Fakultas' }}
                {{ ucwords(str_replace('_',' ',$fakultas)) }}&nbsp;&nbsp;
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="{{ route('daftar.jurusan', ['fakultas' => 'semua_fakultas', 'perHalaman' => $perHalaman]) }}">Semua
                        Fakultas</a></li>
                @foreach($daftarfakultas as $item)
                    <li>
                        <a href="{{ route('daftar.jurusan', ['fakultas' => str_replace(' ','_',strtolower($item->nama)), 'perHalaman' => $perHalaman]) }}">{{ $item->nama }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                {{ $perHalaman }} per halaman&nbsp;&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                @for($per = 5; $per <= $jurusan->total(); $per += 5)
                    <li>
                        <a href="{{ route('daftar.jurusan', ['fakultas' => $fakultas, 'perHalaman' => $per]) }}">{{ $per }}
                            data per halaman</a></li>
                @endfor
                <li>
                <li>
                    <a href="{{ route('daftar.jurusan', ['fakultas' => $fakultas, 'perHalaman' => $jurusan->total()]) }}">Semua
                        Data</a></li>
                </li>
            </ul>
        </div>
    </div>

    <a class="btn btn-success" data-toggle="collapse" data-target="#tambah">Tambah jurusan</a>
    <div id="tambah" class="collapse" role="dialog">
        <div class="card">
            <div class="card-header" data-background-color="blue">
                <h4 class="title">Tambah jurusan</h4>
            </div>
            <div class="card-content">
                <div class="row">
                    <div class="col-md-6">
                        <p>Tambahkan secara manual</p>
                        <form action="{{ route('tambah.jurusan') }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <textarea name="nama" placeholder="Pisahkan dengan enter untuk menambahkan banyak jurusan"
                                      class="form-control" rows="7" required></textarea>
                            <button class="btn btn-success btn-sm" type="button">Tambah</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <p>Tambahkan dengan .csv file dengan format <b>nama_jurusan</b>[splitter]<b>nama_fakultas</b>(opsional)
                        </p>
                        <form action="{{ route('tambah.csv.jurusan') }}" method="post"
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
            <h4 class="title">Jurusan</h4>
            <p class="category">Jumlah jurusan sesuai filter adalah {{ $jurusan->total() }} jurusan</p>
        </div>
        <div class="card-content table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama dan fakultas</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($jurusan as $item)
                    <tr>
                        <td>{{ ($jurusan->currentpage() * $jurusan->perpage()) + (++$c) - $jurusan->perpage()  }}</td>
                        <td>
                            <form action="{{ route('edit.jurusan') }}" method="post" id="simpan-{{ $item->id }}">
                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <span class="material-input"></span>
                                <div class="input-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="nama" value="{{ $item->nama }}"
                                                   class="form-control" style="border: 1px">
                                        </div>
                                        <div class="col-md-6">
                                            <select name="id_fakultas" class="form-control">
                                                @if(is_null($item->id_fakultas))
                                                    <option value="">Pilih Fakultas</option>
                                                    @foreach($daftarfakultas as $value)
                                                        <option value="{{ $value->id }}">
                                                            Fakultas {{ $value->nama }}</option>
                                                    @endforeach
                                                @else
                                                    {{ $jFakultas = \PMW\Models\Jurusan::find($item->id)->fakultas() }}
                                                    <option value="{{ $jFakultas->id }}">
                                                        Fakultas {{ $jFakultas->nama }}</option>
                                                    @foreach($daftarfakultas as $value)
                                                        @if($value->id !== $jFakultas->id)
                                                            <option value="{{ $value->id }}">
                                                                Fakultas {{ $value->nama }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-success btn-sm btn-round"
                                   onclick="event.preventDefault(); document.getElementById('simpan-{{ $item->id }}').submit()">Simpan</a>
                                <a class="btn btn-danger btn-sm btn-round"
                                   onclick="event.preventDefault(); document.getElementById('hapus-{{ $item->id }}').submit()">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <form action="{{ route('hapus.jurusan') }}" method="post" id="hapus-{{ $item->id }}">
                        {{ csrf_field() }}
                        {{ method_field('put') }}
                        <input type="hidden" name="id" value="{{ $item->id }}">
                    </form>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $jurusan->links() }}
        </div>
    </div>
@endsection