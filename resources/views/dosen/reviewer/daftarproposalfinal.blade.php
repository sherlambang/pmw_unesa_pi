@extends('layouts.app')

@section('title')
    Daftar Proposal Final
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}"/>
@endpush

@section('content')
    <div class="card card-nav-tabs">
        <div class="card-header" data-background-color="orange">
            <h4>Daftar Proposal Final</h4>
        </div>

        <div class="card-content no-padding">
            <div class="tab-content">
                <div class="tab-pane active" id="daftar">
                    <table class="table table-hover">
                        <thead class="text-warning">
                            <tr>
                                <th>Judul proposal</th>
                                <th class="hidden-sm hidden-xs">Jenis produk</th>
                                <th class="hidden-sm hidden-xs">Usulan dana</th>
                                <th>Tahap</th>
                                <th class="hidden-sm hidden-xs">Status Nilai</th>
                                <th class="hidden-sm hidden-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daftarproposal->get() as $proposal)
                                <tr>
                                    <td><a target="_blank" href="{{ route('lihat.proposal',[ 'id' => $proposal->id]) }}"> <strong>{{ $proposal->judul }}</strong><sup><i class="fa fa-external-link"></i></sup></a></td>
                                    <td class="hidden-sm hidden-xs">{{ $proposal->jenis_usaha }}</td>
                                    <td class="hidden-sm hidden-xs">{{ $proposal->usulan_dana }}</td>
                                    <td>{{ $proposal->pivot->tahap }}</td>
                                    <td class="hidden-sm hidden-xs">{{ $proposal->sudahDinilaiOleh(Auth::user()->id,$proposal->pivot->tahap) ? 'Sudah dinilai' : 'Belum dinilai' }}</td>
                                    <td class="hidden-sm hidden-xs">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('unduh.proposal.final') }}" class="btn btn-primary unduh-proposal" data-id="{{ $proposal->id }}">Unduh Proposal</a>
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

    <form id="unduh-proposal" action="{{ route('unduh.proposal.final') }}" method="post" style="display: none;">
        {{ csrf_field() }}
        <input type="hidden" name="id"/>
    </form>
@endsection

@push('js')
    <script>
        $('.unduh-proposal').click(function(e){
            e.preventDefault()
            form = $('#unduh-proposal')
            form.find('input[name="id"]').val($(this).attr('data-id'))
            form.submit()
        });
    </script>
@endpush
