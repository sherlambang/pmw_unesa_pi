@extends('layouts.app')

@if(isset($proposal))
    @section('brand','Edit Proposal')
    @section('title','Edit Proposal')
@else
    @section('brand','Unggah Proposal')
    @section('title','Unggah Proposal')
@endif

@push('css')
    <link rel="stylesheet" href="{{ asset('css/tagger.css') }}"/>
@endpush

@section('content')

    <div class="panel-default">
        <div class="panel-heading">
            <h3>{{ isset($proposal) ? 'Edit' : 'Unggah' }} Proposal</h3>
        </div>
        <div class="panel-body">

            <form action="{{ route('unggah.proposal') }}" method="post" enctype="multipart/form-data" id="unggah-proposal">

                <div class="row">

                    <div class="col-lg-6">

                        {{ method_field('patch') }}

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label>Judul</label>
                            <input class="form-control" type="text" name="judul" placeholder="Judul" value="{{ isset($proposal) ? $proposal->judul : '' }}">
                        </div>
                        <div class="form-group">
                            <label>Jenis Usaha</label>
                        </div>
                        <div class="btn-group">
                            <input type="hidden" id="jenis_usaha" name="jenis_usaha" value="{{ isset($proposal) ? $proposal->jenis_usaha : '' }}"/>
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>{{ isset($proposal) ? $proposal->jenis_usaha :  'Pilih Jenis Usaha' }}</span> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" data-target="#jenis_usaha">
                            @foreach(\PMW\Models\Jenis::all() as $jenis)
                                <li data-value="{{ $jenis->id }}"><a href="#">{{ $jenis->nama }}</a></li>
                            @endforeach
                            </ul>
                        </div>
                        <div class="form-group">
                            <label>Usulan Dana</label>
                            <input class="form-control" type="number" name="usulan_dana" placeholder="Usulan dana (Rp)" value="{{ isset($proposal) ? $proposal->usulan_dana : '' }}"/>
                        </div>
                        <div class="form-group">
                            <label>Keyword</label>
                            <input type="hidden" name="keyword" id="keyword" value="{{ isset($proposal) ? $proposal->keyword : '' }}"/>
                            <div class="tagger">
                                @if(isset($proposal))
                                    @foreach (explode('|',$proposal->keyword) as $value)
                                        <div class="tag"><span>{{ $value }}</span><i class="fa fa-close close"></i></div>
                                    @endforeach
                                @endif
                                <input data-target="#keyword" type="text" placeholder="Keyword"/>
                            </div>
                            <p class="alert alert-info">Ketik enter untuk menambahkan keyword</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Abstrak</label>
                        </div>
                        <textarea name="abstrak" id="abstrak">{!! isset($proposal) ? $proposal->abstrak : '' !!}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label>Berkas Proposal</label><br>
                    <div class="input-group">
                        <button type="button" class="btn btn-primary">Pilih Berkas <input type="file" name="berkas"></button>
                        <p class="alert alert-info">Hanya dapat men-upload berkas dengan type file .pdf</p>
                    </div>

                    <span style="display: block" id="nama-berkas"></span>

                    <button type="submit" class="btn btn-warning"><i class="fa fa-save jarak"></i>{{ isset($proposal) ? 'Simpan' : 'Unggah' }} Proposal</button>
                    @if(isset($proposal))
                        <a href="{{ route('proposal') }}" class="btn btn-primary">Batal</a>
                    @endif
                </form>
                <div class="progress" style="display:none" id="progress">
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuemin="0">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </div>

    @endsection

    @push('js')
        <script src="{{ asset('js/jquery.form.js') }}" charset="utf-8"></script>
        <script src="{{ asset('js/tinymce/tinymce.min.js') }}" charset="utf-8"></script>
        <script type="text/javascript">
        tinymce.init({
            selector : 'textarea',
            menubar : false,
            toolbar : 'bold italic underline',
            height : 200
        })

        $('.dropdown-menu').find('li').click(function(e){
            var target = $(this).parent().attr('data-target')
            var text = $(this).parent().prev().find('span').eq(0)

            text.text($(this).text())
            $(target).val($(this).attr('data-value'))
        })

        $('input[name=berkas]').change(function (e) {
            $('#nama-berkas').text($(this).val())
        })

        $('.tagger').find('input[type=text]').on('keydown',function(e){
            var target = $($(this).attr('data-target'))
            var taglist = $(target).val() == '' ? [] : $(target).val().split('|')
            if(e.keyCode == 13){
                e.preventDefault()
                if($(this).val() != '' && taglist.indexOf($(this).val()) == -1){
                    $(this).before('<div class="tag"><span>' + $(this).val() + '</span><i class="fa fa-close close"></i></div>')
                    taglist.push($(this).val())
                    target.val(taglist.join('|'))
                    $(this).val('')
                    $('.tagger').find('.close').click(function(){
                        $(this).parent().remove()
                    })
                }
            }
            if(e.keyCode == 8){
                if($(this).val() == ''){
                    taglist.pop()
                    target.val(taglist.join('|'))
                    $(this).prev().remove()
                }
            }
        })

        $('.tagger').click(function(e){
            $(this).find('input[type=text]').eq(0).focus()
        })

        $('button[type="submit"]').click(function(){
            $('#abstrak').val(tinymce.activeEditor.getContent())
        })

        $('#unggah-proposal').ajaxForm({
            beforeSend : function(){
                $('#progress').show()
                $('button[type="submit"]').attr('disabled','disabled').text('Sedang Mengunggah')
            },
            uploadProgress : function(event, position, total, percentComplete){
                $('#progress').find('.progress-bar').width(percentComplete + "%")
            },
            success : function(response){
                $('#progress').hide()
                $('#progress').find('.progress-bar').width("0%")
                if(response.error == 0) {
                    swal({
                        title : 'Berhasil !',
                        type : 'success',
                        text : response.message
                    }, function() {
                        window.location.reload()
                    })
                }
                else {
                    swal({
                        title : 'Gagal !',
                        type : 'error',
                        text : response.message
                    }, function () {
                        $('button[type="submit"]').removeAttr('disabled').text('Unggah Proposal') 
                    })
                }
            },
            error : function(response) {
                $('#progress').hide()
                $('button[type="submit"]').removeAttr('disabled').text('Unggah Proposal')
                response = response.responseJSON
                text = ''
                if(typeof response.judul !== 'undefined')
                    text = response.judul
                else if(typeof response.usulan_dana !== 'undefined')
                    text = response.usulan_dana
                else if(typeof response.jenis_usaha !== 'undefined')
                    text = response.jenis_usaha
                else if(typeof response.keyword !== 'undefined')
                    text = response.keyword
                else if(typeof response.berkas !== 'undefined')
                    text = response.berkas
                else if(typeof response.abstrak !== 'undefined')
                    text = response.abstrak
                else
                    text = response.responseText
                swal({
                    type : 'error',
                    title : 'Gagal',
                    text : text
                })
            }
        })
        </script>
    @endpush
