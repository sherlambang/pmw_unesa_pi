@extends('layouts.app')

@section('title', 'Dasbor')
@section('brand', 'Dasbor')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            @if(Auth::user()->mahasiswa()->punyaTim())
                @include('mahasiswa.part.daftar_tim')
            @else
                <div class="card">
                    <div class="card-header" data-background-color="green">
                        <h4>Anda belum memiliki tim</h4>
                    </div>
                    <div class="card-content">
                        Ups, anda belum memiliki tim !<br/>
                        Cari tim anda pada menu tim saya atau terima undangan dari mahasiswa lain.
                    </div>
                </div>    
            @endif

            @if($undangan->count() > 0 && !Auth::user()->mahasiswa()->timLengkap())
                <div class="card">
                    <div class="card-header" data-background-color="orange">
                        <h4>Undangan tim</h4>
                    </div>

                    <div class="card-content">
                        Anda mendapat undangan dari <br/>
                        <ul class="list-group">
                            @foreach($undangan->cursor() as $item)
                                <li class="list-group-item">
                                    <b>{{ $item->pengguna()->nama }}</b><br/>
                                    <b>{{ $item->pengguna()->id }}</b>
                                    <form action="{{ route('terima.undangan.tim') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden"  name="dari" value="{{ $item->id_pengguna }}"/>
                                        <button class="btn btn-primary">Terima Undangan</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-6">
            @include('part.linimasa')
        </div>
    </div>

@endsection

@push('js')
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