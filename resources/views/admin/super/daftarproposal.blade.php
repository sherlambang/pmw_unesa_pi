@extends('layouts.app')

@section('brand')
    Proposal
@endsection

@push('css')
<link href="{{ asset('css/form.css') }}" rel="stylesheet"/>
@endpush

@section('content')
    <div class="btn-group">
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                {{ ucwords(str_replace('_', ' ', $fakultas)) }}&nbsp;&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ route('daftar.proposal',['fakultas' => 'semua_fakultas', 'lolos' => $lolos, 'perHalaman' => $perHalaman, 'period' => $period]) }}">Semua
                        Fakultas</a></li>
                @foreach($daftar_fakultas as $item)
                    <li>
                        <a href="{{ route('daftar.proposal',['fakultas' => str_replace(' ','_',strtolower($item->nama)), 'lolos' => $lolos, 'perHalaman' => $perHalaman, 'period' => $period]) }}">Fakultas {{ $item->nama }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                {{ ucwords(str_replace('_', ' ', $lolos)) }}&nbsp;&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ route('daftar.proposal',['fakultas' => $fakultas, 'lolos' => 'semua_proposal', 'perHalaman' => $perHalaman, 'period' => $period]) }}">Semua
                        Proposal</a></li>
                <li><a href="{{ route('daftar.proposal',['fakultas' => $fakultas, 'lolos' => 'tahap_1', 'perHalaman' => $perHalaman, 'period' => $period]) }}">Lolos
                        Tahap 1</a></li>
                <li><a href="{{ route('daftar.proposal',['fakultas' => $fakultas, 'lolos' => 'tahap_2', 'perHalaman' => $perHalaman, 'period' => $period]) }}">Lolos
                        Tahap 2</a></li>
            </ul>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                {{ ucwords(str_replace('_', ' ', $period)) }}&nbsp;&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                @for($th = 2016; $th <= \Carbon\Carbon::now()->year; $th++)
                    <li><a href="{{ route('daftar.proposal',['fakultas' => $fakultas, 'lolos' => $lolos, 'perHalaman' => $perHalaman, 'period' => $th]) }}">Periode {{ $th }}</a></li>
                @endfor
                    <li><a href="{{ route('daftar.proposal',['fakultas' => $fakultas, 'lolos' => $lolos, 'perHalaman' => $perHalaman, 'period' => 'semua_periode']) }}">Semua Periode</a></li>
            </ul>
        </div>
        @if($proposal->total() > 0)
            <a href="{{ route('unduh.filter.proposal', [ 'fakultas' => $fakultas, 'lolos' => $lolos, 'period' => $period]) }}"
               class="btn btn-info">Unduh</a>
        @endif
    </div>
    <div class="btn-group">
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                {{ $perHalaman }} per halaman&nbsp;&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                @for($per = 5; $per <= $proposal->total(); $per += 5)
                    <li>
                        <a href="{{ route('daftar.proposal',['fakultas' => $fakultas, 'lolos' => $lolos, 'perHalaman' => $per, 'period' => $period]) }}">{{ $per }} data per halaman</a>
                    </li>
                @endfor
                <li>
                    <li>
                        <a href="{{ route('daftar.proposal',['fakultas' => $fakultas, 'lolos' => $lolos, 'perHalaman' => $proposal->total(), 'period' => $period]) }}">Semua data</a>
                    </li>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <form action="{{ url()->current() }}" class="card cari">
                <input type="text" placeholder="Cari berdasarkan id atau judul proposal . . ." value="{{ $q }}" id="cari" name="q">
                <button type="submit" id="buttonsearch"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
    
    <!--<div class="row">-->
    <!--    <div class="col-lg-6">-->
    <!--        <form action="{{ url()->current() }}">-->
    <!--            <input type="text" placeholder="Cari berdasarkan id proposal . . ." value="{{ $q }}" id="cari" name="q">-->
    <!--            <button type="submit" id="buttonsearch"><i class="fa fa-search"></i></button>-->
    <!--        </form>-->
    <!--    </div>-->
    <!--</div>-->

    <div class="card">
        <div class="card-header" data-background-color="purple">
            <h4>Daftar proposal</h4>
            <p class="category">Jumlah proposal sesuai filter adalah {{ $proposal->total() }}</p>
            @if($fakultas == 'semua_fakultas' || $period == 'semua_periode')
            @else
            <p class="category">Sudah mengungah tahap pertama adalah, {{$proposal->where('direktori', '!=', NULL)->count()}} proposal |dan| Belum Mengunggah Tahap pertama adalah, {{$proposal->where('direktori', NULL)->count()}} proposal.</p>
            @endif
        </div>
        <div class="card-content">
            @if($proposal->total() == 0)
                <div class="alert alert-info">
                    <h5>Maaf, masih belum ada proposal!</h5>
                </div>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 5%">No.</th>
                        <th style="width: 30%">Judul Proposal</th>
                        <th style="width: 20%">Jenis Usaha</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 30%">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($proposal as $item)
                        <tr>
                            <td>{{ ($proposal->currentpage() * $proposal->perpage()) + (++$c) - $proposal->perpage()  }}</td>
                            <td>
                                {{ $item->judul or 'Belum mengatur judul' }}
                            </td>
                            <td>
                                @if(!is_null($item->jenis_id))
                                {{ \PMW\Models\Jenis::find($item->jenis_id)->nama }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if(isset($item->direktori_final))
                                Sudah Mengunggah Proposal Tahap 2
                                @else
                                    {{ $item->direktori == NULL ? 'Belum Mengunggah' : 'Sudah Mengunggah' }}
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if(!is_null($item->judul))
                                    <a href="{{ route('detail.proposal', ['id' => $item->id]) }}" class="btn btn-info btn-sm">Detail</a>
                                    @endif
                                    <a href="{{ route('edit.reviewer',['idproposal' => $item->id]) }}"
                                       class="btn btn-primary btn-sm">Atur Reviewer</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="card-footer">
            {{ $proposal->links() }}
        </div>
    </div>

@endsection