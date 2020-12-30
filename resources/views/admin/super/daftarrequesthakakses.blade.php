@extends('layouts.app')

@section('brand')
    Permintaan Hak Akses
@endsection

@section('content')
    <div class="card">
        <div class="card-header" data-background-color="purple">
            <h4 class="title">Hak Akses</h4>
            <p class="category">Daftar permintaan hak akses oleh pengguna</p>
        </div>
        <div class="card-content">
            @if($pengguna->count() > 0)
            <table class="table">
                <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Hak akses</th>
                    <th colspan="2">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pengguna as $person)
                    <tr>
                        <td class="col-xs-3">{{ $person->id_pengguna }}</td>
                        <td class="col-xs-5">{{ $person->nama }}</td>
                        <td class="col-xs-3">{{ $person->hakakses }}</td>
                        <td class="col-xs-1">
                            <form action="{{ route('set.terimahakakses') }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                <input type="hidden" name="id_pengguna" value="{{ $person->id_pengguna }}">
                                <input type="hidden" name="id_hak_akses" value="{{ $person->id_hak_akses }}">
                                <input type="submit" name="submit" value="terima" class="btn btn-success">
                            </form>
                        </td>
                        <td class="col-xs-1">
                            <form action="{{ route('set.tolakhakakses') }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                <input type="hidden" name="id_pengguna" value="{{ $person->id_pengguna }}">
                                <input type="hidden" name="id_hak_akses" value="{{ $person->id_hak_akses }}">
                                <input type="submit" name="submit" value="tolak" class="btn btn-danger">
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <div class="alert alert-info"><h4>Belum ada permintaan hak akses saat ini!</h4></div>
            @endif
        </div>
    </div>
@endsection