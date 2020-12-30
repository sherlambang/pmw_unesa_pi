<div class="container">
    <div class="card">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Buka dashboard sebagai
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                @if(Auth::user()->isReviewer())
                    <li><a href="{{ route('dashboard.reviewer')  }}">Reviewer</a></li>
                @endif
                @if(Auth::user()->isDosenPembimbing())
                    <li><a href="{{ route('dashboard.pembimbing')  }}">Dosen Pembimbing</a></li>
                @endif
                    @if(Auth::user()->isSuperAdmin())
                    <li><a href="{{ route('dashboard.superadmin')  }}">Superadmin</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>