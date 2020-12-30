<div class="card">
    <div class="card-header" data-background-color="green">
        <h4>Tim Anda</h4>
    </div>
    <div class="card-content">
        <ul class="list-group">
            @foreach(Auth::user()->mahasiswa()->proposal()->mahasiswa()->cursor() as $anggota)
                <li class="list-group-item">
                    <b>Nama</b><br/>
                    {{ $anggota->pengguna()->nama }} {!! $anggota->pengguna()->isKetua() ? '<b>(Ketua)</b>' : '' !!}
                    <br/><b>NIM</b><br/>
                    {{ $anggota->pengguna()->id }}
                    <br/><b>Prodi/Fakultas</b><br/>
                    {{ $anggota->pengguna()->prodi()->nama }} / Fakultas{{ $anggota->pengguna()->prodi()->jurusan()->fakultas()->nama }}
                </li>
            @endforeach
        </ul>
    </div>
</div>