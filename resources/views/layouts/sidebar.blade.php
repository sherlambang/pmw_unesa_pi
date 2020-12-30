<div class="sidebar" data-color="blue" data-image="{{ asset('img/sidebar-3.jpg') }}">

    <div class="logo">
        <a href="{{ route('dashboard') }}" class="simple-text">
            PMW Unesa
        </a>
    </div>

    <div class="sidebar-wrapper">
        <ul class="nav">
            <li {{ Route::currentRouteName() === 'dashboard' ? 'class=active' : '' }}>
                <a href="{{ route('dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>

            @if (Auth::user()->isSuperAdmin())

                @include('layouts.menus.superadmin')

            @endif

            @if (Auth::user()->isAdminUniversitas())

                @include('layouts.menus.adminuniversitas')

            @endif

            @if (Auth::user()->isAdminFakultas())

                @include('layouts.menus.adminfakultas')

            @endif

            @if(Auth::user()->isMahasiswa())

                @include('layouts.menus.mahasiswa')

            @endif

            @if(Auth::user()->isReviewer())

                @include('layouts.menus.reviewer')

            @endif

            @if(Auth::user()->isDosenPembimbing())

                @include('layouts.menus.dosen')

            @endif
            <li {{ Route::currentRouteName() === 'pengaturan' ? 'class=active' : '' }}>
                <a href="{{ route('pengaturan') }}"><i class="material-icons">face</i><p>Profil</p></a>
            </li>
            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    <i class="material-icons">close</i>
                    <p>Logout</p>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>

        </ul>
    </div>
</div>
