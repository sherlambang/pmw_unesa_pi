<div class="row">
    <div class="col-lg-5">
        @include('mahasiswa.part.daftar_tim')
    </div>

    <div class="col-lg-7">
        @if(Auth::user()->mahasiswa()->proposal()->punyaPembimbing())
            <div class="card">
                <div class="card-header" data-background-color="green">
                    <h4>Dosen Pembimbing</h4>
                </div>
                <div class="card-content">
                    <div class="row">
                        <div class="col-lg-4">
                            <strong>Nama</strong>
                        </div>
                        <div class="col-lg-8">
                            {{ $pembimbing->nama }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <strong>NIP/NIDN</strong>
                        </div>
                        <div class="col-lg-8">
                            {{ $pembimbing->id }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <strong>Prodi</strong>
                        </div>
                        <div class="col-lg-8">
                            {{ Auth::user()->mahasiswa()->proposal()->pembimbing()->prodi()->nama }}, Jurusan {{ Auth::user()->mahasiswa()->proposal()->pembimbing()->prodi()->jurusan()->nama }}, Fakultas {{ Auth::user()->mahasiswa()->proposal()->pembimbing()->prodi()->jurusan()->fakultas()->nama }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <strong>Status</strong>
                        </div>
                        <div class="col-lg-8">
                            @if($pembimbing->pivot->status_request == \PMW\Support\RequestStatus::REJECTED)
                                Permintaan ditolak oleh pembimbing
                                @if(Auth::user()->isKetua())
                                <form action="{{ route('kirimulang.undangan.pembimbing') }}" method="post" class="ajax-form">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="dosen" value="{{ $pembimbing->id }}"/>
                                    <button type="submit" class="btn btn-success btn-sm">Kirim Ulang Permintaan</button>
                                </form>
                                <form action="{{ route('hapus.undangan.pembimbing') }}" method="post" class="ajax-form">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="dosen" value="{{ $pembimbing->id }}"/>
                                    <button type="submit" class="btn btn-danger btn-sm">Batalkan Permintaan</button>
                                </form>
                            @endif
                            @elseif($pembimbing->pivot->status_request == \PMW\Support\RequestStatus::REQUESTING)
                                Menunggu persetujuan dari pembimbing
                                @if(Auth::user()->isKetua())
                                <form action="{{ route('hapus.undangan.pembimbing') }}" method="post" class="ajax-form">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="dosen" value="{{ $pembimbing->id }}"/>
                                    <button type="submit" class="btn btn-danger btn-sm">Batalkan Permintaan</button>
                                </form>
                            @endif
                            @elseif($pembimbing->pivot->status_request == \PMW\Support\RequestStatus::APPROVED)
                                Pembimbing telah menyetujui permintaan
                                @if(Auth::user()->isKetua())
                                <form action="{{ route('hapus.undangan.pembimbing') }}" method="post" class="ajax-form">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="dosen" value="{{ $pembimbing->id }}"/>
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus Pembimbing</button>
                                </form>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p class="alert alert-warning">Tim anda belum memiliki dosen pembimbing</p>
            @if(Auth::user()->isKetua())
                @include('mahasiswa.part.pencarian_pembimbing')
            @endif
        @endif
    </div>
</div>
