@extends('layouts.app')

@section('brand','Dasbor')

@section('title','Dasbor ' . Auth::user()->nama)

@section('content')

    <div class="row">

        <div class="col-lg-6">
            @if(Auth::user()->isDosenPembimbing())
                @include('dosen.pembimbing.dashboard')
            @endif

            @if(Auth::user()->isReviewer())
                @include('dosen.reviewer.dashboard')
            @endif

            @include('part.permintaan_hak_akses')
        </div>

        <div class="col-lg-6">
            @include('part.linimasa')
        </div>

    </div>
    
@endsection

@push('js')
    <script src="{{ asset('js/jquery.form.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/dosen.js') }}" charset="utf-8"></script>
@endpush
