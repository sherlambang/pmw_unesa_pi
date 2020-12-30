@extends('layouts.app')

@section('title')
    Bimbingan
@endsection

@section('brand')
    Bimbingan
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}"/>
@endpush

@section('content')

    <div class="card card-nav-tabs">
        <div class="card-header" data-background-color="orange">
            <h4>Proposal yang Anda Bimbing</h4>
        </div>

        <div class="card-content no-padding">

            @if($jumlahProposalKosong > 0)
                <p style="margin:10px" class="alert alert-primary">Terdapat {{ $jumlahProposalKosong }} tim yang belum mengunggah proposal</p>
            @endif


            @if ($daftarProposal->count() > 0)
                <table class="table table-hover table-expand">
                    <thead class="text-warning">
                    <tr>
                        <th>Judul proposal</th>
                        <th class="hidden-sm hidden-xs">Jenis produk</th>
                        <th class="hidden-sm hidden-xs">Usulan dana</th>
                        <th class="hidden-sm hidden-xs">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($daftarProposal as $proposal)
                        <tr data-proposal="{{ $proposal->id }}">
                            <td><a target="_blank" href="{{ route('detail.proposal',[ 'id' => $proposal->id]) }}">
                                    <strong>{{ $proposal->judul }}</strong><sup><i
                                                class="fa fa-external-link"></i></sup></a></td>
                            <td class="hidden-sm hidden-xs">{{ $proposal->jenis()->nama }}</td>
                            <td class="hidden-sm hidden-xs">{{ Dana::format($proposal->usulan_dana) }}</td>
                            <td class="hidden-sm hidden-xs">
                                <form  style="display: inline" id="unduh-proposal" action="{{ route('unduh.proposal') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $proposal->id }}"/>
                                    @if(!is_null($proposal->direktori))
                                        <button class="btn btn-primary btn-sm" type="submit">Unduh Proposal</button>
                                    @endif
                                </form>
                                @if($proposal->lolos())
                                    <a href="{{ route('lihat.logbook', ['proposal' => $proposal->id]) }}" class="btn btn-primary btn-sm" type="submit">Lihat Logbook</a>
                                @endif
                            </td>
                        </tr>
                        <tr class="expand">
                            <td colspan="6">
                                Sedang memuat...
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div style="margin-left: 10px">
                    {{ $daftarProposal->links() }}
                </div>
            @elseif($jumlahProposalKosong == 0)
                <p class="alert alert-danger" style="margin:10px">Anda belum menjadi pembimbing dari sebuah tim.</p>
            @endif

        </div>
    </div>

@endsection

@push('js')
    <script>
        $('.table-expand').find('tbody').find('tr:not(".expand")').click(function (e) {
            $(this).prevUntil('.table-expand', '.expand').hide()
            $(this).next().nextUntil('.table-expand', '.expand').hide()
            $(this).next().toggle()

            var elem = $(this).next()

            $.ajax({
                url: "{{ route('data.proposal.ajax') }}",
                type: 'get',
                data: 'id=' + $(this).attr('data-proposal'),
                success: function (response) {
                    createExpandedTable(response, elem)
                }
            })
        })

        $('.unduh-proposal').click(function (e) {
            e.preventDefault()
            form = $('#unduh-proposal')
            form.find('input[name="id"]').val($(this).attr('data-id'))
            form.submit()
        });

        var createExpandedTable = function (data, elem) {
            elem.html('<td colspan="6">' + data + '</td>')
        }
    </script>
@endpush
