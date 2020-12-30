<li {{ Route::currentRouteName() === 'info.tim' ? 'class=active' : '' }}>
    <a href="{{ route('info.tim') }}">
        <i class="material-icons">people</i>
        <p>Tim Saya</p>
    </a>
</li>
<li {{ Route::currentRouteName() === 'proposal' ? 'class=active' : '' }}>
    <a href="{{ route('proposal') }}">
        <i class="material-icons">library_books</i>
        <p>Proposal</p>
    </a>
</li>
<li {{ Route::currentRouteName() === 'logbook' ? 'class=active' : '' }}>
    <a href="{{ route('logbook') }}">
        <i class="material-icons">book</i>
        <p>Catatan Harian</p>
    </a>
</li>
<li {{ Route::currentRouteName() === 'laporan' ? 'class=active' : '' }}>
    <a href="{{ route('laporan') }}">
        <i class="material-icons">assignment</i>
        <p>Laporan</p>
    </a>
</li>
