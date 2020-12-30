<li>
    <a data-toggle="collapse" href="#permintaan">
        <i class="material-icons">sms</i>
        <p>Permintaan</p>
    </a>
    <div class="collapse" id="permintaan">
        <ul class="nav">
            <li>
                <a href="{{ route('permintaan.hakakses') }}">Hak Akses</a>
            </li>
        </ul>
    </div>
</li>

<li>
    <a data-toggle="collapse" href="#akademik">
        <i class="material-icons">school</i>
        <p>Akademik</p>
    </a>
    <div class="collapse" id="akademik">
        <ul class="nav">
            <li>
                <a href="{{ route('daftar.fakultas') }}">Fakultas</a>
            </li>
            <li>
                <a href="{{ route('daftar.jurusan', ['fakultas' => 'semua_fakultas', 'perHalaman' => 10]) }}">Jurusan</a>
            </li>
            <li>
                <a href="{{ route('daftar.prodi', ['jurusan' => 'semua_jurusan', 'perHalaman' => 10]) }}">Prodi</a>
            </li>
        </ul>
    </div>
</li>

<li @if(Route::currentRouteName() === 'daftar.pengguna' ) class="active" @endif>
    <a href="{{ route('daftar.pengguna', ['fakultas'=>'semua_fakultas', 'role' => 'semua_hak_akses', 'data' => 50, 'q' => '[]']) }}">
        <i class="material-icons">people</i>
        <p>Pengguna</p>
    </a>
</li>

<li @if(Route::currentRouteName() === 'daftar.proposal' ) class="active" @endif>
    <a href="{{ route('daftar.proposal', ['fakultas' => 'semua_fakultas', 'lolos' => 'semua_proposal', 'perHalaman' => 50, 'period' => 'semua_periode']) }}">
        <i class="material-icons">library_books</i>
        <p>Proposal</p>
    </a>
</li>

<li @if(Route::currentRouteName() === 'pengaturansistem' ) class="active" @endif>
    <a href="{{ route('pengaturansistem') }}">
        <i class="material-icons">settings</i>
        <p>Pengaturan</p>
    </a>
</li>
