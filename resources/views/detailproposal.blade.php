@extends('layouts.app')

@section('title')
{{ $proposal->judul }}
@endsection
@section('brand')
Detail <b>{{ $proposal->judul }}</b>
@endsection

@section('content')
<div class="card">
    <div class="card-content">
        <div class="alert alert-info">
            <p>Proposal ini diunggah {{ $proposal->created_at->diffForHumans() }} ({{ $proposal->created_at }})</p>
        </div>
        
        <h6 class="heading-no-padding"><strong>Judul</strong></h6>
        <h2 class="heading-no-padding">{{ $proposal->judul }}</h2>

        @if(Auth::user()->isMahasiswa())
        <div class="btn-group btn-group-sm">
            @if(Auth::user()->mahasiswa()->bisaEditProposal())
            <a href="{{ route('edit.proposal') }}" class="btn btn-warning btn-sm">Edit Proposal</a>
            @endif

            @if(Auth::user()->mahasiswa()->bisaUnggahProposalFinal())
            <a href="{{ route('unggah.proposal.final') }}" class="btn btn-primary btn-sm">Unggah Proposal Akhir</a>
            @elseif(Auth::user()->mahasiswa()->bisaEditProposalFinal())
            <a href="{{ route('edit.proposal.final') }}" class="btn btn-primary btn-sm">Edit Proposal Akhir</a>
            @endif
        </div>
        @endif
        
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <label>Jenis usaha</label>
                <h5><b>{{ $proposal->jenis()->nama }}</b></h5>
                {{-- Jika user adalah mahasiswa, maka tidak perlu menampilkan daftar review --}}
                @unless(Auth::user()->isMahasiswa())
                <label>Reviewer tahap 1</label>
                @if(!is_null($proposal->daftarReview(1)))
                <ul class="list-group">
                    @foreach($proposal->daftarReview(1)->get() as $item)
                    <li class="list-group-item">
                        @if(!Auth::user()->isMahasiswa() && !Auth::user()->isAdminFakultas())
                            @if(($proposal->punyaPembimbing() && $proposal->pembimbing()->id != Auth::user()->id) || !$proposal->punyaPembimbing())
                                <b>{{ \PMW\User::find($item->id_pengguna)->nama }}</b>
                            @endif
                        @endif
                        @if(!is_null($item->komentar))
                        <br>
                        <label>Komentar</label>
                        <p style="margin: 0">{!! $item->komentar !!}</p>
                        @if(Auth::user()->isSuperAdmin())
                        <label>Nilai</label>
                        <p style="margin: 0">{{ $item->penilaian()->get()->sum('pivot.nilai') }}</p>   
                        @endif
                        @endif
                    </li>
                    @endforeach
                </ul>
                @else
                <br>
                -
                @endif
                @endunless
            </div>
            <div class="col-md-3 col-sm-6">
                <label>Usulan dana</label>
                <h5><b>{{ Dana::format($proposal->usulan_dana) }}</b></h5>
                @unless(Auth::user()->isMahasiswa())
                <label>Reviewer tahap 2</label>
                @if(!is_null($proposal->daftarReview(2)))
                <ul class="list-group">
                    @foreach($proposal->daftarReview(2)->get() as $item)
                    <li class="list-group-item">
                        @if(!Auth::user()->isMahasiswa() && $proposal->pembimbing()->id != Auth::user()->id && !Auth::user()->isAdminFakultas())                   
                        <b>{{ \PMW\User::find($item->id_pengguna)->nama }}</b>
                        @endif
                        @if(!is_null($item->komentar))
                        <br>
                        <label>Komentar</label>
                        <p style="margin: 0;">{!! $item->komentar !!}</p>
                        @if(Auth::user()->isSuperAdmin())
                        <label>Nilai</label>
                        <p style="margin: 0">{{ $item->penilaian()->get()->sum('pivot.nilai') }}</p>   
                        @endif
                        @endif
                    </li>
                    @endforeach
                </ul>
                @else
                <br>
                -
                @endif
                @endunless
            </div>
            <div class="col-md-3 col-sm-6">
                <label>Status penilaian</label>
                <h5><b>{{ $proposal->statusPenilaian() }}</b></h5>
                @if(Auth::user()->isSuperAdmin())
                <label>Nilai rata-rata</label>
                <ul class="list-group">
                    <ul class="list-group-item">
                        <p><b>Tahap 1</b><br>{{ $proposal->nilaiRataRata(1) }}</p>
                    </ul>
                    <ul class="list-group-item">
                        <p><b>Tahap 2</b><br>{{ $proposal->nilaiRataRata(2) }}</p>
                    </ul>
                </ul>
                @endif
            </div>
            <div class="col-md-3 col-sm-6">
                <label>Unduh proposal</label>
                <br>
                <div class="btn-group">
                    <a class="btn btn-primary btn-sm" href="#" onclick="event.preventDefault();document.getElementById('unduh-proposal').submit();">
                        Awal
                    </a>
                    @if(!is_null($proposal->direktori_final))
                    <a class="btn btn-success btn-sm" href="#" onclick="event.preventDefault();document.getElementById('unduh-proposal-final').submit();">
                        Akhir
                    </a>
                    @endif
                </div>

                <form id="unduh-proposal" action="{{ route('unduh.proposal') }}" method="post"
                          style="display: none;">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $proposal->id }}" name="id">
                </form>
                <form id="unduh-proposal-final" action="{{ route('unduh.proposal.final') }}" method="post"
                          style="display: none;">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $proposal->id }}" name="id">                    
                </form>

                @unless(Auth::user()->isMahasiswa())
                <label style="display: block">Pembimbing & Tim</label>
                <ul class="list-group">
                    <li class="list-group-item">
                        <label style="display: block">Pembimbing</label>
                        @if($proposal->punyaPembimbing())
                        <b>{{ $proposal->pembimbing()->nama }}</b>
                        <br>
                        {{ $proposal->pembimbing()->id }}
                        @else
                        @endif
                    </li>
                    <li class="list-group-item">
                        <label>Tim</label>
                        @foreach($proposal->mahasiswa()->get() as $item)
                        <br>
                        <b>
                            {{ $item->pengguna()->nama }}
                            @if($item->pengguna()->isKetua())
                            (ketua)
                            @else
                            (anggota)
                            @endif
                        </b>
                        <br>
                        {{ $item->pengguna()->id }}
                        @endforeach
                    </li>
                </ul>
                @endunless
            </div>
        </div>

        @if($proposal->lolos())
        <div class="row">
            <div class="col-lg-6">
                <label>Unduh Laporan</label>
                <br/>
                <div class="btn-group btn-group-sm">
                    @if($proposal->laporanKemajuan())
                    <button class="btn btn-primary" onclick="event.preventDefault();document.getElementById('unduh-laporan-kemajuan').submit();">Kemajuan</button>
                    @endif
                    @if($proposal->laporanAkhir())
                    <button class="btn btn-primary" onclick="event.preventDefault();document.getElementById('unduh-laporan-akhir').submit();">Akhir</button>
                    @endif
                    @if($proposal->laporanMagang())
                    <button class="btn btn-primary" onclick="event.preventDefault();document.getElementById('unduh-laporan-magang').submit();">Magang</button>
                    @endif
                    @if($proposal->laporanKeuangan())
                    <button class="btn btn-primary" onclick="event.preventDefault();document.getElementById('unduh-laporan-keuangan').submit();">Keuangan</button>
                    @endif
                </div>
                
                @if($proposal->laporanKemajuan())
                <form id="unduh-laporan-kemajuan" action="{{ route('unduh.laporan.kemajuan') }}" method="post"
                          style="display: none;">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $proposal->id }}" name="id_proposal">                    
                </form>
                @endif
                @if($proposal->laporanAkhir())
                <form id="unduh-laporan-akhir" action="{{ route('unduh.laporan.akhir') }}" method="post"
                          style="display: none;">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $proposal->id }}" name="id_proposal">                    
                </form>
                @endif
                @if($proposal->laporanMagang())
                <form id="unduh-laporan-magang" action="{{ route('unduh.laporan.magang') }}" method="post"
                          style="display: none;">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $proposal->id }}" name="id_proposal">                    
                </form>
                @endif
                @if($proposal->laporanKeuangan())                
                <form id="unduh-laporan-keuangan" action="{{ route('unduh.laporan.keuangan') }}" method="post"
                          style="display: none;">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $proposal->id }}" name="id_proposal">                    
                </form>
                @endif
            </div>
            <div class="col-lg-6">
                <label>Aksi Lainnya</label>
                <br/>
                <div class="btn-group btn-group-sm">
                    <a href="{{ Auth::user()->isMahasiswa() ? route('logbook') : route('lihat.logbook', ['proposal' => $proposal->id]) }}" class="btn btn-primary">Lihat Catatan Harian</a>
                </div>
            </div>
        </div>
        @endif
        
        
        <br/>
        <label>Abstrak</label>
        <p>{!! $proposal->abstrak !!}</p>
        <p><i>keyword : </i>{{ $proposal->keyword() }}</p>
        
        @if(Auth::user()->isMahasiswa())
        <div class="row">
            <div class="col-md-6">
                <h6><strong>Hasil review tahap 1</strong></h6>
                @if(!is_null($proposal->daftarReview(1)))
                <p> <b>{{ $proposal->daftarReview(1)->whereNotNull('komentar')->count() }}</b> dari total <b>{{ $proposal->daftarReview(1)->count() }}</b> reviewer telah melakukan penilaian </p>
                <ul class="list-group">
                    @foreach($proposal->daftarReview(1)->get() as $item)
                    @if(!is_null($item->komentar))
                    <li class="list-group-item">
                        <b>Komentar</b>
                        <p>{!! $item->komentar !!}</p>
                    </li>
                    @endif
                    @endforeach
                </ul>
                @else
                <br>
                <p>Proposal ini belum dinilai</p>
                @endif
            </div>
            
            <div class="col-md-6">
                <h6><strong>Hasil review tahap 2</strong></h6>
                @if(!is_null($proposal->daftarReview(2)))
                <p> <b>{{ $proposal->daftarReview(2)->whereNotNull('komentar')->count() }}</b> dari total <b>{{ $proposal->daftarReview(2)->count() }}</b> reviewer telah melakukan penilaian </p>
                <ul class="list-group">
                    @foreach($proposal->daftarReview(2)->get() as $item)
                    @if(!is_null($item->komentar))
                    <li class="list-group-item">
                        <b>Komentar</b>
                        <p>{!! $item->komentar !!}</p>
                    </li>
                    @endif
                    @endforeach
                </ul>
                @else
                <br>
                <p>Proposal ini belum dinilai</p>
                @endif
            </div>
        </div>
        @endif
        
    </div>
</div>
@endsection