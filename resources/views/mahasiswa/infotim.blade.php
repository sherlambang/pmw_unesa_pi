@extends('layouts.app')

@section('title', 'Informasi Tim')
@section('brand', 'Informasi Tim')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}"/>
@endpush

@section('content')

    {{-- Jika tim belum lengkap, maka akan menampilkan daftar undangan dan pencarian anggota --}}
    @if(!Auth::user()->mahasiswa()->punyaTim() || !Auth::user()->mahasiswa()->timLengkap())
        <div class="row">
            <div class="col-lg-4 col-md-4">
                @if(Auth::user()->mahasiswa()->punyaTim())
                    @include('mahasiswa.part.daftar_tim')
                @endif
                @if(Auth::user()->mahasiswa()->undanganTimAnggota()->count() > 0)
                    @include('mahasiswa.part.undangan_tim_yang_diterima')
                @endif
                    @include('mahasiswa.part.undangan_tim_yang_dikirim')

                {{--
                @if(!Auth::user()->mahasiswa()->punyaTim() || (Auth::user()->isKetua() && !Auth::user()->mahasiswa()->timLengkap()))
                    <div class="card">
                        <div class="card-header" data-background-color="green">
                            <h4>Opsi Tim/Kelompok</h4>
                        </div>
                        <div class="card-content">
                            <button class="btn btn-primary" id="individu">Konfirmasi tim saya saat ini</button>
                            <p class="alert alert-info">
                                Klik tombol diatas jika anda ingin mengikuti PMW secara individu atau dengan 1 anggota saja
                            </p>
                        </div>
                    </div>
                @endif
                --}}
            </div>
            <div class="col-lg-8 cl-md-8">
                @include('mahasiswa.part.pencarian_anggota')
            </div>
        </div>

    @else
    {{-- Jika tim sudah lengkap, maka menampilkan informasi tim beserta dosen pembimbing --}}
        @include('mahasiswa.part.info_tim')
    @endif

    @push('js')
        <script src="{{ asset('js/jquery.form.js') }}"></script>
        @if(!Auth::user()->mahasiswa()->timLengkap())
        <script src="{{ asset('js/undangantim.js') }}"></script>
        @else
        <script src="{{ asset('js/undanganpembimbing.js') }}"></script>
        @endif

        <script>
            $('#individu').click(function () {
                swal({
                    type: 'warning',
                    title: 'Apa anda yakin ?',
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                }, function () {
                    $.ajax({
                        url: '{{ route('mahasiswa.konfirmasi.tim') }}',
                        type: 'POST',
                        success: function (response) {
                            if (response.error == 0) {
                                swal({
                                    type: 'success',
                                    title: 'Berhasil',
                                    text: response.message  
                                }, function () {
                                    window.location.reload()
                                })
                            }
                        },
                        error: function (response) {

                        }
                    })
                })
            })
        </script>

        @if(Session::has('message'))
            <script>
                swal({
                    type: '{{ Session::get('error') == 0 ? 'success' : 'error' }}',
                    text: '{{ Session::get('message') }}',
                    title: '{{ Session::get('error') == 0 ? 'Berhasil' : 'Gagal' }}'
                })
            </script>
        @endif
    @endpush

@endsection
