@include('layouts.header')

@if(Auth::check())

    @include('layouts.sidebar')

    <div class="main-panel">
        <nav style="background-color: #ffffff" class="navbar navbar-transparent navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">@yield('brand')</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a class="navbar-brand">
                                {{-- Auth::user()->nama --}}
                                Bidang Kemahasiswaan dan Alumni
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
@endif

<div class="content" style="width: 100%">
    <div class="container-fluid">
        @yield('content')
    </div>
</div>

@include('layouts.footer')
