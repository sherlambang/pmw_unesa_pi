@extends('layouts.app')

@section('brand','Laporan')

@section('title','Laporan')

@section('content')

    @if(Auth::user()->mahasiswa()->punyaTim())
        @if(Auth::user()->mahasiswa()->punyaProposal())
        @if(Auth::user()->mahasiswa()->proposal()->lolos())
            @include('mahasiswa.part.info_laporan')
        @else
            <div class="card">
                <div class="card-content">
                    <p class="alert alert-primary">Tim anda belum dinyatakan lolos. Anda bisa berpartisipasi lagi tahun depan. Terima kasih telah ikut menyukseskan program ini dan sukses selalu bersama kita semua demi Unesa jaya yang kita banggakan.</p>
                </div>
            </div>
        @endif
        @else
            <div class="card">
            <div class="card-content">
                <p class="alert alert-primary">Ketua tim anda belum mengunggah proposal</p>
            </div>
        </div>
        @endif
    @else

        <div class="card">
            <div class="card-content">
                <p class="alert alert-primary">Anda belum memiliki tim</p>
            </div>
        </div>

    @endif

@endsection
