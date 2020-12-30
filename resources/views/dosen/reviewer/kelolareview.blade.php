@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/radio.css') }}"/>
@endpush

@section('content')

    <div class="card">

        <div class="card-header" data-background-color="red">

            <h4>{{ $type == 'edit' ? 'Edit' : 'Beri' }} Nilai pada proposal <strong>{{ $proposal->judul }}</strong></h4>
            <span><b>Tahap {{ $proposal->pivot->tahap }}</b></span>

        </div>

        <div class="card-content">

            @if(Session::has('message'))
                <p class="alert alert-{{ Session::get('error') ? 'danger': 'success' }}">
                    {{ Session::get('message') }}
                </p>
            @endif

            @if($errors->count() > 0 && $errors->has('message'))
                <p class="alert alert-danger">
                    {{ $errors->first('message') }}
                </p>
            @endif

            <form action="{{ $type == 'edit' ? route('edit.nilai.review',['id'=>$proposal->pivot->id]) : route('tambah.nilai.review',['id'=>$proposal->pivot->id]) }}"
                  method="post">

                {{ csrf_field() }}

                {{ $type == 'edit' ? method_field('patch') : method_field('put') }}

                <div class="row">
                    <div class="col-lg-6">
                        <table class="table">
                            <thead class="text-danger">
                            <tr>
                                <th>Aspek</th>
                                <th>Nilai</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($daftaraspek as $index => $aspek)
                                <tr>
                                    <td>{{ $aspek->nama }}</td>
                                    <td>
                                        @for($i = 1;$i <= 5;$i++)
                                            <input id="{{ $aspek->nama . $i }}"
                                                   type="radio"
                                                   name="nilai[{{ $aspek->id }}]"
                                                   value="{{ $i }}" {{ ($type == 'edit' && $penilaian->get()[$index]->pivot->nilai == $i) || ($errors->has('message') && key_exists($index + 1, old('nilai')) && old('nilai')[$index + 1] == $i) ? 'checked' : '' }}/><label for="{{ $aspek->nama . $i }}"><span></span>{{ $i }}</label>
                                            &nbsp;
                                        @endfor
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        @if($errors->has('komentar'))
                            <p class="alert alert-danger">
                                {{ $errors->first('komentar') }}
                            </p>
                        @endif
                                <textarea placeholder="Komentar" class="form-control"
                                          name="komentar">
                                    @if($type == 'edit')
                                        {{ $proposal->pivot->komentar }}
                                    @elseif($errors->has('komentar'))
                                        {{ old('komentar') }}
                                    @endif
                                </textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-lg-offset-5">
                        <input style="width: 100%" class="btn btn-danger" type="submit" value="Kirim"/>
                    </div>
                </div>

            </form>

        </div>

    </div>

@endsection

@push('js')
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            menubar : false,
            toolbar : 'bold italic underline',
            height : 200
        })
    </script>
@endpush
