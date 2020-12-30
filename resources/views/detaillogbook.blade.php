@extends('layouts.app')

@section('title')
{{ $proposal->judul }}
@endsection
@section('brand')
Logbook <b>{{ $proposal->judul }}</b>
@endsection
@section('content')


        <div class="card" id="logbook-header" style="{{ $errors->count() > 0 ? 'display:none' : '' }}">
            <div class="card-content">
                <h6 class="heading-no-padding"><strong>Kegiatan</strong></h6>
                <h5 class="heading-no-padding">{{ $logbook->catatan }}</h5>
                <br>
                <div class="row">
                    <div class="col-md-5 col-sm-6">
                        <label>Biaya</label>
                        <h5><b>{{ Dana::format($logbook->biaya) }}</b></h5>
                        <label>Waktu Kegiatan</label>
                        <h5 class="alert alert-info"><b>{{ $logbook->created_at->diffForHumans() }} ({{ $logbook->created_at }})</b></h5>
                    </div>
                    <div class="col-md-7 col-sm-6">
                        <label>Foto Kegiatan</label>
                        <br>
                        <img  style="width: 100%; height: auto;" src="{{ asset('upload/logbook/' . $logbook->direktori_foto ) }}">
                    </div>
                </div>
                <br>
                <label>Lokasi Foto Diambil</label>
                <br>
                <iframe frameborder="0" src="https://maps.google.com/?q={{ $location['latitude']}},{{$location['longitude']}}&output=svembed" height="300" width="100%" ></iframe>
            </div>
        </div>

        

@endsection
