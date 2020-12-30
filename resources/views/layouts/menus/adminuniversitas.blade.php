<li @if(Route::currentRouteName() === 'proposaladminuniv' ) class="active" @endif>
    <a href="{{ route('proposaladminuniv', ['fakultas' => 'semua_fakultas', 'lolos' => 'semua', 'perHalaman' => 10, 'period' => 'semua_periode']) }}">
        <i class="material-icons">library_books</i>
        <p>Proposal</p>
    </a>
</li>