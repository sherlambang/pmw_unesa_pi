@extends('layouts.app')

@section('brand')
    Fakultas
@endsection

@section('content')
    {{--alert--}}
    @if(session()->has('message'))
        <div class="alert alert-info">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header" data-background-color="purple">
                    <h4 class="title">Fakultas</h4>
                    <p class="category">Daftar fakultas</p>
                </div>
                <div class="card-content table-responsive">
                    <table class="table" id="fakultas">
                        <thead class="text-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fakultas as $item)
                            <tr>
                                <td>{{ ++$c }}</td>
                                <td>
                                    <form action="{{ route('edit.fakultas') }}" method="post"
                                          id="simpan-{{ $item->id }}">
                                        {{ csrf_field() }}
                                        {{ method_field('put') }}
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <span class="material-input"></span>
                                        <div class="input-group">
                                            <input type="text" name="nama" value="{{ $item->nama }}"
                                                   class="form-control" style="border: 1px">
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('hapus.fakultas') }}" method="post" id="hapus-{{ $item->id  }}">
                                        {{ csrf_field() }}
                                        {{ method_field('put') }}
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                    </form>
                                    <div class="btn-group">
                                        <a class="btn btn-success btn-sm btn-round"
                                           onclick="event.preventDefault(); document.getElementById('simpan-{{ $item->id }}').submit()">Simpan</a>
                                        <a class="btn btn-danger btn-sm btn-round"
                                           onclick="event.preventDefault(); document.getElementById('hapus-{{ $item->id }}').submit()">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header" data-background-color="blue">
                    <h4 class="title">Tambah fakultas</h4>
                    <p class="category">Tambah data fakultas secara manual atau .csv file</p>
                </div>
                <div class="card-content">
                    <h5>Manual</h5>
                    <form action="{{ route('tambah.fakultas') }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <textarea name="nama" placeholder="Pisahkan dengan enter untuk menambahkan banyak fakultas"
                                  class="form-control"></textarea>
                        <input type="submit" name="submit" value="tambah" class="btn btn-success btn-round btn-sm">

                    </form>
                    <hr>
                    <h5>.csv file</h5>
                    <form action="{{ route('tambah.csv.fakultas') }}" method="post"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="input-group input-group-sm">
                            <label>Pilih file</label>
                            <input name="csv" type="file" accept=".csv" required><br>
                            <button class="btn btn-success btn-round btn-sm" type="submit">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#fakultas').DataTable({
            responsive: true,
            "info": false,
            "sort": false,
            "searching": false,
            "lengthMenu": [[5, 10, 20, 40, 80, 100, -1], [5, 10, 20, 40, 80, 100, "Semua data"]],
        });
    });
</script>
@endpush