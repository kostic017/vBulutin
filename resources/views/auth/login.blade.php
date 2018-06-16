@extends('layouts.app')

@section('content')
    <div class="card-header">
        {{ __('auth.login') }}
    </div>

    <div class="card-body">
        <form id="captcha-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __("auth.name-or-mail") }}</label>
                <div class="col-md-6">
                    <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('auth.password') }}</label>
                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" required>
                    @if ($errors->any())
                        <span class="invalid-feedback" style="display:block">
                            <ul class="m-0 list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li><strong>{{ $error }}</strong></li>
                                @endforeach
                            </ul>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 offset-md-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('auth.remember') }}
                        </label>
                    </div>
                </div>
            </div>

            @include('includes.recaptcha', ['recaptcha_action' => __('auth.login')])
            <p><a class="btn btn-link" href="{{ route('password.request') }}">{{ __('auth.forgot') }}</a></p>

        </form>
    </div>
@endsection
