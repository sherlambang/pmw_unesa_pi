@if(Session::has('message'))
    <p class="alert alert-success alert-dismissible">
        <button style="top:0;right:0" type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        <span>{{ Session::get('message') }}</span>
    </p>
@endif

@if(Session::has('message_danger'))
    <p class="alert alert-danger alert-dismissible">
        <button style="top:0;right:0" type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        <span>{{ Session::get('message_danger') }}</span>
    </p>
@endif

<div class="card" style="{{ $errors->count() == 0 ? 'display:none' : '' }}"
     id="wrapper-form-logbook">

    <div class="card-header" data-background-color="red">
        <h5>Tambah Logbook</h5>
    </div>

    <div class="card-content">
        <div class="alert alert-primary" role="alert">
          <h4 class="alert-heading">Cara Pengisian Logbook</h4>
          <p>Harap dibaca ya !</p>
          <hr>
          <p class="mb-0">1. Pastikan hari dan tanggal benar.<br>2. Lengkapi data form dibawa ini.<br>3. Foto kegiatan yang di upload pastikan terdapat data lokasi (latitude dan longitude) di atributnya.<br>4. Data lokasi pada foto dapat dilihat pada rincian atau properties foto sebelum di upload.<br>5. Isi biaya sesuai kebutuhan yang dikeluarkan.<br>6. Isi catatan sebagai pelengkap data pada logbook.</p>
        </div>

        <form action="{{ route('tambah.logbook') }}" method="post" enctype="multipart/form-data">

            {{ csrf_field() }}

            {{ method_field('put') }}

            <input type="hidden" name="tanggal" value="{{ Carbon\Carbon::today() }}"/>

            <div class="row">
                <div class="col-lg-2">
                    <label>Hari dan Tanggal</label>
                </div>
                <div class="col-lg-5">
                    <p>{{ Carbon\Carbon::today()->formatLocalized('%A, %d %B %Y') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2">
                    <label for="biaya">Foto Kegiatan</label>
                    <br>
                    <!-- <h10 class="data-background-color">Pastikan terdapat keterangan lokasi pada detail foto</h10> -->
                </div>
                <div class="col-lg-5">

                    <input type="file" accept="image/jpg,image/jpeg" capture="camera" name="camera" id="camera" required="" />
                    <br>
                    <img id="frame" style="width: 100%; height: auto;">

                    @if($errors->has('camera'))
                        <p class="alert alert-danger">{{ $errors->first('camera') }}</p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2">
                    <label for="biaya">Biaya</label>
                </div>
                <div class="col-lg-5">
                    <input class="form-control" type="number" name="biaya" placeholder="biaya"
                           value="{{ old('biaya') }}" required/>
                    @if($errors->has('biaya'))
                        <p class="alert alert-danger">{{ $errors->first('biaya') }}</p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2">
                    <label for="catatan">Catatan</label>
                </div>
                <div class="col-lg-5">
                                <textarea name="catatan" class="form-control" id="catatan" 
                                          placeholder="Catatan" required>{{ old('catatan') }}</textarea>
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
                        <button type="button" class="btn btn-primary" id="batal-tambah-logbook">Batal
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')
<script src="{{ asset('js/geotag.js') }}"></script>
@endpush
