<div class="card">
    <div class="card-header" data-background-color="purple">
        <h4>Undangan yang sedang dikirim</h4>
    </div>
    <div class="card-content" id="undangan-wrapper">
        @if(Auth::user()->mahasiswa()->undanganTimKetua()->count() > 0)
        <table class="table" id="undangan-yang-dikirim">
            <thead class="text-primary">
                <tr>
                    <th>Penerima</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach (Auth::user()->mahasiswa()->undanganTimKetua()->get() as $undangan)
                    <tr id="dikirim-{{ $undangan->pengguna()->id }}">
                    <td>
                        <b>{{ $undangan->pengguna()->nama }}</b><br/>
                        {{ $undangan->pengguna()->id }}<br/>
                        {!! $undangan->pivot->ditolak ? '<b style=color:red>Ditolak</b>' : '' !!}
                    </td>
                    <td>
                        <div class="btn-group btn-group-vertical">
                            @if($undangan->pivot->ditolak)
                                <button class="btn btn-primary btn-sm kirimulang-undangan" data-id="{{ $undangan->pengguna()->id }}" data-nama="{{ $undangan->pengguna()->nama }}">Kirim Ulang</button>
                            @endif
                            <button class="btn btn-danger btn-sm hapus-undangan" data-id="{{ $undangan->pengguna()->id }}">Hapus</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <form action="{{ route('kirimulang.undangan.tim') }}" method="post" id="kirimulang-undangan">
            {{ csrf_field() }}
            <input type="hidden" name="id"/>
        </form>

        @else
            <p class="alert alert-warning">Maaf, anda belum mengirim undangan ke siapapun</p>
        @endif
        <form action="{{ route('hapus.undangan.tim') }}" method="post" id="form-hapus-undangan">
            {{ csrf_field() }}
            <input type="hidden" name="id"/>
        </form>
    </div>
</div>
