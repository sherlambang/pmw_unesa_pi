<div class="row">
    <div class="col-lg-6">
        {{-- Laporan Kemajuan --}}
        <div class="card">
            <div class="card-header" data-background-color="green">
                <h5 class="title">
                    Laporan Kemajuan
                </h5>
            </div>

            <div class="card-content">
                @if(Auth::user()->mahasiswa()->proposal()->lolos())
                    @include('mahasiswa.part.form_laporan',[
                        'type' => 'kemajuan'
                    ])
                @else
                    <p class="alert alert-warning">Proposal tim anda belum lolos</p>
                @endif
            </div>
        </div>

        {{-- Laporan Magang --}}
        <div class="card">
            <div class="card-header" data-background-color="green">
                <h5 class="title">
                    Laporan Magang
                </h5>
            </div>

            <div class="card-content">
                @if(Auth::user()->mahasiswa()->proposal()->lolos())
                    @include('mahasiswa.part.form_laporan',[
                        'type' => 'magang'
                    ])
                @else
                    <p class="alert alert-warning">Proposal tim anda belum lolos</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        {{-- Laporan Akhir  --}}
        <div class="card">
            <div class="card-header" data-background-color="green">
                <h5 class="title">
                    Laporan Akhir
                </h5>
            </div>

            <div class="card-content">
                @if(Auth::user()->mahasiswa()->proposal()->lolos())
                    @include('mahasiswa.part.form_laporan',[
                        'type' => 'akhir'
                    ])
                @else
                    <p class="alert alert-warning">Proposal tim anda belum lolos</p>
                @endif
            </div>
        </div>
        
        {{-- Laporan Keuangan  --}}
        <div class="card">
            <div class="card-header" data-background-color="green">
                <h5 class="title">
                    Laporan Keuangan
                </h5>
            </div>

            <div class="card-content">
                @if(Auth::user()->mahasiswa()->proposal()->lolos())
                    @include('mahasiswa.part.form_laporan',[
                        'type' => 'keuangan'
                    ])
                @else
                    <p class="alert alert-warning">Proposal tim anda belum lolos</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('js/jquery.form.js') }}"></script>
    <script>
        $(function () {

            var form;

            $('button[type="submit"]').click(function () {
                $('button[type="submit"]').attr('disabled', 'disabled')
                form = $(this).parent().parent().parent()
                form.submit()
            })

            $('form.upload').ajaxForm({
                beforeSend: function () {
                    form.find('.progress').show()
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    form.find('.progress-bar').width(percentComplete + "%")
                },
                success: function (response) {
                    swal({
                        type: response.type,
                        title: (response.type == 'error') ? 'Gagal !' : 'Berhasil !',
                        text: response.message
                    }, function () {
                        window.location.reload()
                    })
                },
                error: function (response) {
                    swal({
                        type: 'error',
                        title: 'Gagal !',
                        text: 'Terjadi kesalahan sistem, cobalah beberapa saat lagi !'
                    }, function () {
                        $('button[type="submit"]').removeAttr('disabled')
                        $('.progress').hide()
                    })
                }
            })

            $('.edit-laporan').click(function () {
                var target = $($(this).attr('data-target'))
                target.show()
            })
        })
    </script>
@endpush