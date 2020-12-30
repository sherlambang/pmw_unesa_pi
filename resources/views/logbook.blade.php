@extends('layouts.app')

@section('brand', "Logbook")
@section('title', "Logbook")

@section('content')

    @if($proposal->lolos())

        <div class="card" id="logbook-header" style="{{ $errors->count() > 0 ? 'display:none' : '' }}">
            <div class="card-content">
                <div class="row">
                    <div class="col-lg-10">
                        <h4 class="heading-no-padding"><strong>Logbook dari {{ $proposal->judul }}</strong></h4>
                        <h6 style="vertical-align:middle" class="heading-no-padding">Tim ini
                            memiliki <strong>{{ $proposal->logbook()->count() }}</strong> logbook</h6>
                    </div>
                </div>
            </div>
        </div>

        @if($proposal->logbook()->count() > 0)
            @include('mahasiswa.part.daftar_logbook', [
                'daftarlogbook' => $daftarlogbook
            ])
        @endif

    @else

        <div class="card">

            <div class="card-content">
                <p class="alert alert-primary">Tim belum dinyatakan lolos</p>
            </div>

        </div>

    @endif

@endsection
