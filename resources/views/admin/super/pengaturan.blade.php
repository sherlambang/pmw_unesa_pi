@extends('layouts.app')

@section('brand')
    Pengaturan
@endsection

@section('content')
    @if(session()->has('message'))
        <br>
        <div class="alert alert-info">
            {{ session()->get('message') }}
        </div>
    @endif

    <br>

    <div class="card">
        <div class="card-header" data-background-color="orange">
            <h4 class="title">Proposal</h4>
        </div>
        <div class="card-content">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="card">
                        <div class="card-header" data-background-color="orange">
                            <h4 class="title">{{ $pengaturan[0]->nama }}</h4>
                        </div>
                        <div class="card-content">
                            <form action="{{ route('edit.pengaturan') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $pengaturan[0]->id }}" min="0" max="100">
                                <input type="number" name="keterangan" value="{{ $pengaturan[0]->keterangan }}"
                                       class="form-control">
                                <input type="submit" name="submit" value="simpan" class="btn btn-success">
                            </form>
                            <p>Diubah {{ $pengaturan[0]->updated_at->diffForHumans() }}.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" data-background-color="orange">
                            <h4 class="title">{{ $pengaturan[1]->nama }}</h4>
                        </div>
                        <div class="card-content">
                            <form action="{{ route('edit.pengaturan') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $pengaturan[1]->id }}">
                                <input id="pengumpulan-proposal" type="text" name="keterangan"
                                       value="{{ $pengaturan[1]->keterangan }}" class="form-control">
                                <input type="submit" name="submit" value="simpan" class="btn btn-success">
                            </form>
                            <p>Diubah {{ $pengaturan[1]->updated_at->diffForHumans() }}.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" data-background-color="orange">
                            <h4 class="title">{{ $pengaturan[3]->nama }}</h4>
                        </div>
                        <div class="card-content">
                            <form action="{{ route('edit.pengaturan') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $pengaturan[3]->id }}">
                                <input id="pengumpulan-proposal-final" type="text" name="keterangan"
                                       value="{{ $pengaturan[3]->keterangan }}" class="form-control">
                                <input type="submit" name="submit" value="simpan" class="btn btn-success">
                            </form>
                            <p>Diubah {{ $pengaturan[3]->updated_at->diffForHumans() }}.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="card">
                        <div class="card-header" data-background-color="orange">
                            <h4 class="title">{{ $pengaturan[2]->nama }}</h4>
                        </div>
                        <div class="card-content">
                            <form action="{{ route('edit.pengaturan') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $pengaturan[2]->id }}">
                                <input id="penilaian-1" type="text" name="keterangan" value="{{ $pengaturan[2]->keterangan }}"
                                       class="form-control dtp-show">
                                <input type="submit" name="submit" value="simpan" class="btn btn-success">
                            </form>
                            <p>Diubah {{ $pengaturan[2]->updated_at->diffForHumans() }}.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" data-background-color="orange">
                            <h4 class="title">{{ $pengaturan[4]->nama }}</h4>
                        </div>
                        <div class="card-content">
                            <form action="{{ route('edit.pengaturan') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $pengaturan[4]->id }}">
                                <input id="penilaian-2" type="text" name="keterangan" value="{{ $pengaturan[4]->keterangan }}"
                                       class="form-control dtp-show">
                                <input type="submit" name="submit" value="simpan" class="btn btn-success">
                            </form>
                            <p>Diubah {{ $pengaturan[4]->updated_at->diffForHumans() }}.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header" data-background-color="green">
                    <h4 class="title">Tambah Jenis</h4>
                </div>
                <div class="card-content">
                    <form action="{{ route('tambah.jenis') }}" method="post">
                        {{ csrf_field() }}
                        <textarea name="nama" class="form-control"
                                  placeholder="Pisahkan dengan enter untuk menambahkan banyak jenis"></textarea>
                        <input type="submit" name="submit" value="tambah" class="btn btn-success">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header" data-background-color="blue">
                    <h4>Daftar jenis</h4>
                    <p class="category">Berikut ini adalah jenis usaha pada PMW</p>
                </div>
                <div class="card-content table-responsive">
                    <table class="table" id="jenis">
                        <thead>
                        <tr>
                            <td>No</td>
                            <td>Nama</td>
                            <td>Aksi</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\PMW\Models\Jenis::all() as $jenis)
                            <tr>
                                <td>{{ ++$n }}</td>
                                <td>
                                    <form action="{{ route('edit.jenis') }}" method="post" id="ujenis-{{ $jenis->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{ $jenis->id }}">
                                        <input type="text" name="nama" value="{{ $jenis->nama }}" class="form-control">
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-success btn-sm" onclick="event.preventDefault(); document.getElementById('ujenis-{{ $jenis->id }}').submit();">Simpan</button>
                                        <button class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('hjenis-{{ $jenis->id }}').submit();">Hapus</button>
                                        <form action="{{ route('hapus.jenis') }}" method="post" id="hjenis-{{ $jenis->id }}">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{ $jenis->id }}">
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header" data-background-color="purple">
                    <h4 class="title">Tambah Aspek</h4>
                </div>
                <div class="card-content">
                    <form action="{{ route('tambah.aspek') }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('put') }}
                        <textarea name="nama" class="form-control" placeholder="Pisahkan dengan enter untuk menambahkan banyak aspek"></textarea>
                        <div class="input-group">
                            <p>Tahap</p>
                            <select class="form-control" name="tahap">
                                <option value="1">Tahap 1</option>
                                <option value="2">Tahap 2</option>
                            </select>
                        </div>
                        <input type="submit" name="submit" value="tambah" class="btn btn-success">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header" data-background-color="blue">
                    <h4>Daftar aspek</h4>
                    <p class="category">Berikut ini adalah aspek atau kriteria PMW UNESA</p>
                </div>
                <div class="card-content table-responsive">
                    <table class="table" id="aspek">
                        <thead>
                        <tr>
                            <td width="10%">No.</td>
                            <td width="50%">Nama Aspek</td>
                            <td width="5%">Tahap</td>
                            <td width="35%">Aksi</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aspek as $item)
                            <tr>
                                <td>
                                    {{ ++$c }}
                                </td>
                                <td>
                                    <form action="{{ route('edit.aspek') }}" method="post" id="update-{{ $item->id }}">
                                        {{ csrf_field() }}
                                        {{ method_field('put') }}
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" style="width: 280px">
                                    </form>
                                </td>
                                <td>{{ $item->tahap }}</td>
                                <td>
                                    <div class="btn-group">
                                        <form action="{{ route('hapus.aspek') }}" method="post" id="hapus-{{ $item->id }}" style="display: none">
                                            {{ csrf_field() }}
                                            {{ method_field('put') }}
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                        </form>
                                        <a href="{{ route('edit.aspek') }}" class="btn btn-success btn-sm"
                                           onclick="event.preventDefault(); document.getElementById('update-{{ $item->id }}').submit();">Simpan</a>
                                        <a href="{{ route('hapus.aspek') }}"
                                           onclick="event.preventDefault();document.getElementById('hapus-{{ $item->id }}').submit();"
                                           class="btn btn-danger btn-sm">Hapus</a>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#aspek').DataTable({
                responsive: true,
                "info": false,
                columnDefs: [{orderable: false, targets: [0, 1, 3]}],
                "searching": false,
                "lengthMenu": [[5, 10, 20, 40, 80, 100, -1], [5, 10, 20, 40, 80, 100, "Semua data"]],
            });
        });
        $(document).ready(function () {
            $('#jenis').DataTable({
                responsive: true,
                "info": false,
                "sort": false,
                "searching": false,
                "lengthMenu": [[5, 10, 20, 40, 80, 100, -1], [5, 10, 20, 40, 80, 100, "Semua data"]],
            });
        });
        $(document).ready(function()
        {
            $('#pengumpulan-proposal-final').bootstrapMaterialDatePicker
            ({
                format: 'YYYY-MM-DD HH:mm',
                lang: 'id',
                weekStart: 0,
                cancelText : 'Batal',
                nowText : 'Sekarang',
                nowButton : true,
                switchOnClick : true
            });
            $('#pengumpulan-proposal-final').bootstrapMaterialDatePicker('setMinDate', $('#pengumpulan-proposal').val());
            $('#pengumpulan-proposal').bootstrapMaterialDatePicker
            ({
                format: 'YYYY-MM-DD HH:mm',
                lang: 'id',
                weekStart: 0,
                cancelText : 'Batal',
                nowText : 'Sekarang',
                nowButton : true,
                switchOnClick : true
            }).on('change', function (e, date) {
                $('#pengumpulan-proposal-final').bootstrapMaterialDatePicker('setMinDate', date);
            });

            $('#penilaian-2').bootstrapMaterialDatePicker
            ({
                format: 'YYYY-MM-DD HH:mm',
                lang: 'id',
                weekStart: 0,
                cancelText : 'Batal',
                nowText : 'Sekarang',
                nowButton : true,
                switchOnClick : true
            });
            $('#penilaian-2').bootstrapMaterialDatePicker('setMinDate', $('#penilaian-1').val());
            $('#penilaian-1').bootstrapMaterialDatePicker
            ({
                format: 'YYYY-MM-DD HH:mm',
                lang: 'id',
                weekStart: 0,
                cancelText : 'Batal',
                nowText : 'Sekarang',
                nowButton : true,
                switchOnClick : true
            }).on('change', function (e, date) {
                $('#penilaian-2').bootstrapMaterialDatePicker('setMinDate', date);
            });
            $.material.init()
        });
    </script>
@endpush