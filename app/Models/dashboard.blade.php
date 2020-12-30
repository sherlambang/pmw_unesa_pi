
@if(Auth::user()->punyaUndanganBimbingan())
<div class="card">
    <div class="card-header" data-background-color="red">
        <h4 class="title">Undangan Bimbingan</h4>
        <p class="category">Anda diminta untuk membimbing mahasiswa berikut</p>
    </div>
    
    <div class="card-content">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Informasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach($undangan->cursor() as $proposal)
                @if(!is_null($proposal->ketua()))
                <tr>
                    <td>
                        {{ $proposal->ketua()->nama }}
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('terima.undangan.dosen') }}"
                            data-proposal="{{ $proposal->id }}" class="btn btn-success terima-undangan">Terima</a>
                            <a href="{{ route('tolak.undangan.dosen') }}"
                            data-proposal="{{ $proposal->id }}" class="btn btn-danger tolak-undangan">Tolak</a>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach --}}
            </tbody>
        </table>
    </div>
</div>
@endif

@if(Auth::user()->punyaBimbingan())
<div class="card">
    <div class="card-header" data-background-color="green">
        <h4 class="title">Tim Bimbingan Anda</h4>
        <p class="category">Berikut adalah tim yang berada dibawah bimbingan anda</p>
    </div>
    
    <div class="card-content">
        @if($bimbingan->count() > 0)
        Anda telah menjadi pembimbing dari mahasiswa berikut :
        <table class="table table-responsive table-hover">
            <thead>
                <tr>
                    <th>Nama Ketua Tim</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bimbingan->cursor() as $proposal)
                <tr>
                    <td>
                        {{ $proposal->ketua()->nama }}
                    </td>
                    <td>
                    @if(!is_null($proposal->judul))
                    <a class="btn btn-primary btn-sm" href="{{ route('lihat.proposal',['id' => $proposal->id]) }}">Lihat Proposal</a>
                    @else
                    Belum mengunggah proposal
                    @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endif
