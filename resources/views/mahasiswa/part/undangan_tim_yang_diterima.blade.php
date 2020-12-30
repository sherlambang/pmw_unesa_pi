@if(Auth::user()->mahasiswa()->undanganTimAnggota()->wherePivot('ditolak', false)->count() > 0)
    <div class="card">
        <div class="card-header" data-background-color="purple">
            <h4>Undangan tim yang diterima</h4>
        </div>
        <div class="card-content">

            <table class="table" id="undangan-yang-diterima">
                <thead class="text-info">
                    <th>Pengirim</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach (Auth::user()->mahasiswa()->undanganTimAnggota()->wherePivot('ditolak',false)->get() as $undangan)
                        <tr id="terima-{{ $undangan->pengguna()->id }}">
                            <td><b>{{ $undangan->pengguna()->nama }}</b><br/>{{ $undangan->pengguna()->id }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm terima-undangan" data-id="{{ $undangan->pengguna()->id }}">Terima</button>
                                    <button class="btn btn-danger btn-sm tolak-undangan" data-id="{{ $undangan->pengguna()->id }}">Tolak</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('terima.undangan.tim') }}" method="post" id="terimaundangan">
                {{ csrf_field() }}
                <input type="hidden" name="dari"/>
            </form>

            <form action="{{ route('tolak.undangan.tim') }}" method="post" id="tolakundangan">
                {{ csrf_field() }}
                <input type="hidden" name="dari"/>
            </form>
        </div>
    </div>
@endif
