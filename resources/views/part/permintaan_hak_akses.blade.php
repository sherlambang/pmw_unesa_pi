<div class="card">
        <div class="card-header" data-background-color="green">
            <h4>Permintaan Hak Akses</h4>
        </div>
        
        <div class="card-content">
            @if(Auth::user()->bisaRequestHakAkses(\PMW\Models\HakAkses::DOSEN_PEMBIMBING))
            <form action="{{ route('request.pembimbing') }}" class="ajax-form" method="post">
                {{ csrf_field() }}
                <input type="submit" value="Request menjadi dosen pembimbing" class="btn btn-primary"/>
            </form>
            @elseif(Auth::user()->requestingHakAkses(\PMW\Models\HakAkses::DOSEN_PEMBIMBING))
            <p class="alert alert-info">Anda sedang menunggu persetujuan untuk menjadi dosen pembimbing</p>
            @endif

            {{-- @if(Auth::user()->bisaRequestHakAkses(\PMW\Models\HakAkses::REVIEWER))
            <form action="{{ route('request.reviewer') }}" class="ajax-form" method="post">
                {{ csrf_field() }}
                <input type="submit" value="Request menjadi reviewer" class="btn btn-primary"/>
            </form>
            @elseif(Auth::user()->requestingHakAkses(\PMW\Models\HakAkses::REVIEWER))
            <p class="alert alert-info">Anda sedang menunggu persetujuan untuk menjadi reviewer</p>
            @endif --}}

            @if(Auth::user()->isDosenPembimbing())
            <p class="alert alert-info">
                    Anda tidak bisa melakukan <i>request</i> hak akses
                </p>
            @endif
            
            <p class="alert alert-warning">
                Anda perlu menunggu sampai admin menerima <i>request</i> anda
            </p>
        </div>
    </div>