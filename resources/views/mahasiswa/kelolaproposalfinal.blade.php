@extends('layouts.app')

@if($type == 'edit')
    @section('brand','Edit Proposal Final')
    @section('title','Edit Proposal Final')
@else
    @section('brand','Unggah Proposal Final')
    @section('title','Unggah Proposal Final')
@endif

@push('css')
    <link rel="stylesheet" href="{{ asset('css/tagger.css') }}"/>
@endpush

@section('content')
    <div class="card">

        <div class="card-header" data-background-color="blue">
            <h4>{{ $type == 'edit' ? 'Edit' : 'Unggah' }} Proposal Final</h4>
        </div>

        <div class="card-content">
            <form action="{{ route('unggah.proposal.final') }}" method="post" enctype="multipart/form-data" id="unggah-proposal-final">

                {{ csrf_field() }}
    
                {{ method_field('put') }}

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Judul</label>
                            <input class="form-control" type="text" name="judul" placeholder="Judul" value="{{ $proposal->judul}}">
                        </div>
                        <div class="form-group">
                            <label>Jenis Usaha</label>
                        </div>
                        <div class="btn-group">
                            <input type="hidden" id="jenis_usaha" name="jenis_usaha" value="{{ $proposal->jenis()->id }}"/>
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>{{ $proposal->jenis()->nama }}</span> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" data-target="#jenis_usaha">
                                @foreach(\PMW\Models\Jenis::all() as $jenis)
                                    <li data-value="{{ $jenis->id }}"><a href="#">{{ $jenis->nama }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="form-group">
                            <label>Usulan Dana</label>
                            <input class="form-control" type="number" name="usulan_dana" placeholder="Usulan dana (Rp)" value="{{ $proposal->usulan_dana }}"/>
                        </div>
                        <div class="form-group">
                            <label>Keyword</label>
                            <input type="hidden" name="keyword" id="keyword" value="{{ $type == 'edit' ? $proposal->keyword : '' }}"/>
                            <div class="tagger">
                                    @foreach (explode('|',$proposal->keyword) as $value)
                                        <div class="tag"><span>{{ $value }}</span><i class="fa fa-close close"></i></div>
                                    @endforeach
                                <input data-target="#keyword" type="text" placeholder="Keyword"/>
                            </div>
                        </div>
                        <div class="form-group">
                    <label>Berkas Proposal</label><br>
                    <div class="input-group">
                        <button type="button" class="btn btn-primary">Pilih Berkas <input type="file" name="berkas"></button>
                    </div>
                </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Abstrak</label>
                        </div>
                        <textarea name="abstrak" id="abstrak">{!! $proposal->abstrak !!}</textarea>
                    </div>
                </div>
    
                <button id="btn-unggah" type="submit" class="btn btn-success">Unggah Proposal Final</button>
    
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
<script>
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
        
    $('#unggah-proposal-final').ajaxForm({
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
            swal({
                title : response.error ? 'Gagal !' : 'Berhasil !',
                type : response.error ? 'error' : 'success',
                text : response.message
            }, function() {
                if(!response.error)
                    window.location.replace(response.redirect)
            })
        },
        error : function(response){
            $('#btn-unggah').attr('disabled', 'false')
        }
    })
</script>
@endpush