<form class="card cari" action="{{ route('cari.mahasiswa') }}" method="get" id="cari-anggota">
    <input  type="text" name="nama" placeholder="Cari Mahasiswa" autofocus/>
    <button type="submit"><i class="fa fa-search"></i></button>
</form>

<div class="card">
    <div class="card-header" data-background-color="blue">
        <h4>Hasil Pencarian</h4>
    </div>
    <div class="card-content table-responsive">
        <div id="belum-cari" style="text-align:center;">
            <i class="fa fa-user fa-5x"></i>
            <p style="font-size:30px;padding-top:10px;">Mulailah untuk mencari anggota timmu</p>
        </div>
        <div id="not-found" style="text-align:center;display:none">
            <i class="fa fa-user-times fa-5x"></i>
            <p style="font-size:30px;padding-top:10px;">Tidak menemukan user yang anda cari</p>
        </div>
        <table class="table table-striped" style="display:none">
            <thead>
                <tr class="text-default">
                    <th>Nama & NIM</th>
                    <th>Asal Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="hasil-pencarian">
            </tbody>
        </table>
        <div id="hasil-pencarian"></div>
        <form action="{{ route('undang.anggota') }}" method="post" style="display: none" id="undang-anggota">
            {{ csrf_field() }}
            <input type="hidden" name="untuk" />
        </form>
    </div>
</div>
