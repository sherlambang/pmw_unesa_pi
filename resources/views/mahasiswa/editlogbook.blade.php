@extends('layouts.app')

@section('title','Edit Logbook')

@section('brand','Edit Logbook')

@section('content')
    <div class="card" id="wrapper-form-logbook">
        <div class="card-content">
            <h5>Edit Catatan Harian</h5>
            <form action="{{ route('edit.logbook') }}" method="post" enctype="multipart/form-data">

                {{ csrf_field() }}

                {{ method_field('patch') }}

                <input type="hidden" name="id" value="{{ $logbook->id }}">

                <div class="row">
                    <div class="col-lg-2">
                        <label>Tanggal<label>
                        </div>
                        <div class="col-lg-5">
                            <input name="tanggal" id="tanggal" class="form-control" type="text" value="{{ $logbook->tanggal }}">
                            <span id="locale">{{ Carbon\Carbon::parse($logbook->tanggal)->formatLocalized('%A, %d %B %Y') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <label for="biaya">Foto Kegiatan</label>
                        </div>
                        <div class="col-lg-5">
                            <input  type="file" accept="image/jpg,image/jpeg" capture="camera" name="camera" id="camera" />
                            <br>
                            <img id="frame" src="{{ $errors->has('direktori_foto') ? old('direktori_foto') : asset('upload/logbook/' . $logbook->direktori_foto ) }}" style="width: 100%; height: auto;">
                            @if($errors->has('camera'))
                                <p class="alert alert-danger">{{ $errors->first('camera') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <label for="biaya">Biaya<label>
                            </div>
                            <div class="col-lg-5">
                                <input class="form-control" type="number" name="biaya" placeholder="biaya" value="{{ $errors->has('biaya') ? old('biaya') : $logbook->biaya }}"/>
                                @if($errors->has('biaya'))
                                    <p class="alert alert-danger">{{ $errors->first('biaya') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="catatan">Catatan<label>
                                </div>
                                <div class="col-lg-5">
                                    <textarea name="catatan" class="form-control" placeholder="Catatan">{{ $errors->has('catatan') ? old('catatan') : $logbook->catatan }}</textarea>
                                    @if($errors->has('catatan'))
                                        <p class="alert alert-danger">{{ $errors->first('catatan') }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-2">
                                </div>
                                <div class="col-lg-5">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                        <a href="{{ route('logbook') }}" type="button" class="btn btn-primary">Batal</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            @endsection

@push('js')

<script src="{{ asset('js/geotag.js') }}"></script>
<script>
$('#tanggal').bootstrapMaterialDatePicker({
    format: 'YYYY-MM-DD',
    lang: 'id',
    weekStart: 0,
    cancelText : 'Batal',
    nowText : 'Sekarang',
    nowButton : true,
    switchOnClick : true
}).on('change', function (e, date) {
    event = new Date(Date.parse($(this).val()))
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    $('#locale').text(event.toLocaleDateString('id-ID', options))
});
</script>

@endpush