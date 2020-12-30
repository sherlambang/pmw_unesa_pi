<h2>Total jumlah proposal saat ini</h2>

<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <div class="table-responsive">
        <table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
          <caption class="text-center"></caption>
          <thead>
            <tr>
              <th>No</th>
              <th>Fakultas</th>
              <th colspan="2">Proposal</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
             <tr>
              <td></td>
              <td></td>
              <td>Sudah Mengunggah</td>
              <td>Bekum Mengunggah</td>
              <td></td>
            </tr>
            @php $x = 0 @endphp
            @foreach(PMW\Models\Fakultas::orderBy('nama', 'asc')->get() as $prodi)
            <tr>
              <td>{{$x++}}</td>
              <td>{{$prodi->nama}}</td>
              <td>41,803,125</td>
              <td>31.3</td>
              <td>{{$proposal->pengguna->first()}}</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" class="text-center">Data retrieved from <a href="http://www.infoplease.com/ipa/A0855611.html" target="_blank">infoplease</a> and <a href="http://www.worldometers.info/world-population/population-by-country/" target="_blank">worldometers</a>.</td>
            </tr>
          </tfoot>
        </table>
      </div><!--end of .table-responsive-->
    </div>
  </div>
</div>

<p class="p">Demo by George Martsoukos. <a href="http://www.sitepoint.com/responsive-data-tables-comprehensive-list-solutions" target="_blank">See article</a>.</p>
