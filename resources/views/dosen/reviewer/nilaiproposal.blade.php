@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header" data-background-color="orange">
            <h4>Nilai untuk proposal : <strong>{{ $proposal->judul }}</strong></h4>
            <h6>Tahap {{ $proposal->pivot->tahap }}</h6>
        </div>
        <div class="card-content">
            <table class="table table-hover">
                <thead class="text-warning">
                <tr>
                    <th>Nama Aspek</th>
                    <th>Nilai</th>
                </tr>
                </thead>
                <tbody>
                @foreach($penilaian->get() as $nilai)
                    <tr>
                        <td>{{ $nilai->nama }}</td>
                        <td>{{ $nilai->pivot->nilai }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h4>Komentar anda terhadap proposal tersebut</h4>
            <div class="alert alert-primary">
                {!! $proposal->pivot->komentar !!}
            </div>

            @if(!\PMW\Models\Pengaturan::melewatiBatasPenilaian($proposal->pivot->tahap))
                <a href="{{ route('edit.nilai.review',['id' => $proposal->pivot->id]) }}"
                   class="btn btn-primary">Edit</a>
            @endif

        </div>
    </div>

@endsection