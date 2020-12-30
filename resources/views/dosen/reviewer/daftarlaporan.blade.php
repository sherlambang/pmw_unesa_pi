@extends('layouts.app')

@section('title')
    Daftar Laporan {{ Route::currentRouteName() === 'daftar.laporan.kemajuan' ? 'Kemajuan' : 'Akhir' }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}"/>
@endpush

@section('content')

    <div class="card card-nav-tabs">
        <div class="card-header" data-background-color="orange">
            <h4>Daftar Laporan {{ Route::currentRouteName() === 'daftar.laporan.kemajuan' ? 'Kemajuan' : 'Akhir' }}</h4>
        </div>

        <div class="card-content no-padding">
            <div class="tab-content">
                <table class="table table-hover">
                        <thead class="text-warning">
                            <tr>
                                <th>Judul proposal</th>
                                <th class="hidden-sm hidden-xs">Jenis produk</th>
                                <th class="hidden-sm hidden-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daftarlaporan->get() as $laporan)
                                <tr>
                                    <td><a target="_blank" href="{{ route('lihat.proposal',[ 'id' => $laporan->proposal()->id]) }}"> <strong>{{ $laporan->proposal()->judul }}</strong><sup><i class="fa fa-external-link"></i></sup></a></td>
                                    <td class="hidden-sm hidden-xs">{{ $laporan->proposal()->jenis_usaha }}</td>
                                    <td class="hidden-sm hidden-xs">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ Route::currentRouteName() === 'daftar.laporan.kemajuan' ?  route('unduh.laporan.kemajuan') : route('unduh.laporan.akhir') }}" class="btn btn-primary unduh-laporan" data-id="{{ $laporan->proposal()->id }}">Unduh Laporan</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>

    <form id="unduh-laporan" action="{{ Route::currentRouteName() === 'daftar.laporan.kemajuan' ? route('unduh.laporan.kemajuan') : route('unduh.laporan.akhir') }}" method="post" style="display: none;">
        {{ csrf_field() }}
        <input type="hidden" name="id_proposal"/>
    </form>

@endsection

@push('js')
    <script>
        $('.unduh-laporan').click(function(e){
            e.preventDefault()
            form = $('#unduh-laporan')
            form.find('input[name="id_proposal"]').val($(this).attr('data-id'))
            form.submit()
        });
    </script>
@endpush
