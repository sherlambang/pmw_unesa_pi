@extends('layouts.app')

@section('brand', "Catatan Harian")
@section('title', "Catatan Harian")

@section('content')

    @if(Auth::user()->mahasiswa()->punyaTim())
        @if(Auth::user()->mahasiswa()->punyaProposal())
        @if(Auth::user()->mahasiswa()->proposal()->lolos())

            <div class="card" id="logbook-header" style="{{ $errors->count() > 0 ? 'display:none' : '' }}">
                <div class="card-content">
                    <div class="row">
                        <div class="col-lg-10">
                            <h5 style="vertical-align:middle;margin-top:20px">Tim anda
                                memiliki {{ Auth::user()->mahasiswa()->proposal()->logbook()->count() }} catatan</h5>
                        </div>

                        @if(Auth::user()->isKetua())
                            <div class="col-lg-2">
                                <button id="tampilkan-form-logbook" class="btn btn-primary"
                                        style="width:100%;text-align:center;padding:12px 0">Tambah Catatan Harian
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if(Auth::user()->isKetua())
                @include('mahasiswa.part.form_tambah_logbook')
            @endif

            @if(Auth::user()->mahasiswa()->proposal()->logbook()->count() > 0)
                @include('mahasiswa.part.daftar_logbook', [
                    'daftarlogbook' => $daftarlogbook
                ])
            @endif

        @else
            <div class="card">
                <div class="card-content">
                    <p class="alert alert-primary">Tim anda belum dinyatakan lolos. Anda bisa menambahkan logbook setelah tim anda dinyatakan lolos tahap 2</p>
                </div>
            </div>
        @endif
        @else
            <div class="card">
                <div class="card-content">
                    <p class="alert alert-primary">Tim anda belum mengunggah proposal</p>
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

@if(Auth::user()->mahasiswa()->punyaProposal() && Auth::user()->mahasiswa()->punyaProposal())
    @push('js')

        <script src="{{ asset('js/geotag.js') }}"></script>
        <script type="text/javascript">
            $('#tampilkan-form-logbook').click(function (e) {
                $('#wrapper-form-logbook').show()
                $('#logbook-header').hide()
            })

            $('#batal-tambah-logbook').click(function () {
                $('#wrapper-form-logbook').hide()
                $('#logbook-header').show()
            })

            $('.hapus-logbook').click(function (e) {
                e.preventDefault()
                var obj = $(this)
                swal({
                        title: "Apa anda yakin ?",
                        text: "Logbook akan dihapus secara permanen !",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: 'Batal',
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ya, hapus!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function () {
                        $.ajax({
                            type: 'delete',
                            url: obj.attr('href'),
                            data: 'id=' + obj.attr('data-id'),
                            success: function (response) {
                                swal({
                                    title: (response.type == 'success') ? 'Berhasil !' : 'Gagal !',
                                    text: response.message,
                                    type: response.type
                                }, function () {
                                    window.location.reload()
                                });
                            }
                        })
                    });
            })
        </script>
    @endpush
@endif
