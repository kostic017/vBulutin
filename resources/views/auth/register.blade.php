@extends('layouts.auth')

@section('content')
    <div class="card-header">
        {{ __('auth.register') }}
    </div>

    <div class="card-body">
        <form id="captcha-form" method="post" action="{{ route('register') }}">
            @csrf

            <div class="form-group row">
                <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('auth.username') }}</label>

                <div class="col-md-6">
                    <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" autofocus>
                    @include('includes.error', ['error_key' => 'username'])
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('auth.email') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">
                    @include('includes.error', ['error_key' => 'email'])
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('auth.password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                    @include('includes.error', ['error_key' => 'password'])
                </div>
            </div>

            <div class="form-group row">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('auth.password2') }}</label>
                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    @include('includes.recaptcha', ['recaptcha_action' => __('auth.register')])
                </div>
            </div>
        </form>
    </div>
@endsection
