@extends('layouts.app')

@section('title', 'Atur Reviewer')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/chooser.css') }}"/>
@endpush

@section('content')
    <div class="card">
        <div class="card-header" data-background-color="green">
            <h4 class="title">Atur Reviewer Untuk <strong>{{ $proposal->judul }}</strong> </h4>
            <p class="category">Atur reviewer dari sebuah proposal tertentu</p>
        </div>

        <div class="card-content">
            <div class="row">
                <div class="col-lg-6">
                    <h5>Tahap 1</h5>
                    <form action="{{ route('edit.reviewer', ['idproposal' => $proposal->id]) }}" method="post" id="kelolatahap1">
                        {{ csrf_field() }}
                        {{ method_field('patch') }}
                        <input type="hidden" name="tahap" value="1"/>
                        <input type="hidden" name="daftar_pengguna" value="{{ implode(',', $oldreviewer['tahap1']->pluck('pengguna.id')->toArray()) }}" id="tahap1"/>
                        <div class="chooser">
                            @foreach ($oldreviewer['tahap1']->get() as $reviewer)
                                <div class="choosed" data-index="{{ $reviewer->id }}">
                                    <span>{{ $reviewer->nama }}</span>
                                    @if(!\PMW\Models\Pengaturan::melewatiBatasPenilaian(1))
                                    <i class="fa fa-close close"></i>
                                    @endif
                                </div>
                            @endforeach
                            @if(!\PMW\Models\Pengaturan::melewatiBatasPenilaian(1))
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>Pilih Reviewer</span> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" data-target="#tahap1">
                                    <input type="text" class="form-control"/>
                                    @foreach ($daftarreviewer as $reviewer)
                                        <li data-id="{{ $reviewer->id }}" {{ in_array($reviewer->id, $oldreviewer['tahap1']->pluck('pengguna.id')->toArray()) ? 'style=display:none' : '' }}><a href="#">{{ $reviewer->nama }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
                @if(\PMW\Models\Proposal::find($proposal->id)->lolos(1))
                    <div class="col-lg-6">
                        <h5>Tahap 2</h5>
                        <form action="{{ route('edit.reviewer',['idproposal' => $proposal->id]) }}" method="post" id="kelolatahap2">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}
                            <input type="hidden" name="tahap" value="2"/>
                            <input type="hidden" name="daftar_pengguna" value="{{ implode(',', $oldreviewer['tahap2']->pluck('pengguna.id')->toArray()) }}" id="tahap2"/>
                            <div class="chooser">
                                @foreach ($oldreviewer['tahap2']->get() as $reviewer)
                                    <div class="choosed" data-index="{{ $reviewer->id }}">
                                        <span>{{ $reviewer->nama }}</span>
                                    @if(!\PMW\Models\Pengaturan::melewatiBatasPenilaian(2))
                                        <i class="fa fa-close close"></i>
                                        @endif
                                    </div>
                                @endforeach
                            @if(!\PMW\Models\Pengaturan::melewatiBatasPenilaian(2))                         
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span>Pilih Reviewer</span> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" data-target="#tahap2">
                                        <input type="text" class="form-control"/>
                                        @foreach ($daftarreviewer2 as $reviewer)
                                            <li data-id="{{ $reviewer->id }}" {{ in_array($reviewer->id, $oldreviewer['tahap2']->pluck('pengguna.id')->toArray()) ? 'style=display:none' : '' }}><a href="#">{{ $reviewer->nama }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-lg-2 col-lg-offset-5">
                    <a class="btn btn-primary" style="width:100%" id="save">Simpan</a>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/jquery.form.js') }}"></script>
    <script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        })

        $('#kelolatahap1').ajaxForm({
            success : function(response){
                @if(\PMW\Models\Proposal::find($proposal->id)->lolos(1))
                    $('#kelolatahap2').submit()
                @else
                swal({
                    title : 'Berhasil !',
                    text : 'Anda baru saja memperbarui reviewer !',
                    type : 'success'},function(){
                    window.history.back()
                })
                @endif
            }
        })

        $('#kelolatahap2').ajaxForm({
            success : function(response){
                swal({
                    title : 'Berhasil !',
                    text : 'Anda baru saja memperbarui reviewer !',
                    type : 'success'},function(){
                        window.history.back()
                    })
            }
        })

        $('#save').click(function(){
            $('#kelolatahap1').submit()
        })

        // Chooser
        $('.chooser').find('.dropdown-menu li').click(function(e){
            e.preventDefault()
            var btn = $(this).parent().parent()
            var dropdown = $(this).parent()
            var target =  $(dropdown.attr('data-target'))
            var val = target.val() == '' ? [] :  target.val().split(',')
            val.push($(this).attr('data-id'))
            target.val(val)
            {{--  console.log(val)  --}}
            btn.before('<div data-index="'+($(this).attr('data-id'))+'" class="choosed"><span>' + $(this).text() + '</span><i class="fa fa-close close"></i></div>')
            $(this).hide()
            $('.chooser').find('.close').click(function(){
                var dropdown = $(this).parent().parent().find('.dropdown-menu')
                var li = dropdown.find('li[data-id=' + $(this).parent().attr('data-index') + ']')
                li.show()
                var val = target.val().split(',')
                val.splice(val.indexOf(li.attr('id')),1)
                target.val(val)
                {{--  console.log(val)  --}}
                $(this).parent().remove();
            })
        })

        $('.chooser').find('.close').click(function(){
            var dropdown = $(this).parent().parent().find('.dropdown-menu')
            var li = dropdown.find('li[data-id=' + $(this).parent().attr('data-index') + ']')
            var target = $(dropdown.attr('data-target'))
            li.show()
            var val = target.val().split(',')
            {{--  console.log(val)  --}}
            val.splice(val.indexOf(li.attr('data-id')),1)
            target.val(val)
            console.log(val)
            $(this).parent().remove()
        })

        $('.dropdown-menu').find('input[type="text"]').on('keyup',function(e){
            e.preventDefault()
            var dropdown = $(this).parent().parent()
            var val = $(this).val()
            var target = $(dropdown.attr('data-target'))
            dropdown.find("li").each(function(){
                {{--  console.log('dnjasdn')  --}}
                if($(this).text().toLowerCase().indexOf(val.toLowerCase()) == -1)
                    $(this).hide()
                else{
                    {{--  console.log(target.val().indexOf($(this).attr('data-id')))  --}}
                    if(target.val().indexOf($(this).attr('data-id')) == -1)
                        $(this).show()
                }
            })
        })
    })
    </script>
@endpush
