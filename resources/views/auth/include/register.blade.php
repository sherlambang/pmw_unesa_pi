<div id="daftar" class="{{ request()->has('tab') && request()->get('tab') == 'register' ? 'active' : '' }} tab-pane fade in">
    <div class="panel-default login-panel panel">
        @if(request()->has('message') && request()->has('tab') && request()->get('tab') === 'register')
            <p class="alert alert-success">
                {{ request()->get('message') }}
            </p>
        @endif
        <div class="panel-body">
            <form role="form" action="{{ route('register') }}" method="post">
                {{ csrf_field() }}
                <fieldset>
                    <div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
                        <label style="color: #111111">NIM/NIP</label>
                        <input class="form-control nim" placeholder="NIM/NIP" name="id" id="id" type="text" value="{{ old('id') }}" required autofocus
                            autocomplete="on"> @if ($errors->has('id'))
                        <span class="text-danger">
                            <strong>{{ $errors->first('id') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label style="color: #111111">Email</label>
                        <input class="form-control" placeholder="Email" name="email" type="email" value="{{ old('email') }}" required autocomplete="on"> @if ($errors->has('email'))
                        <span class="text-danger">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-9 col-lg-3">
                            <button type="submit" class="btn btn-primary">Daftar</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>