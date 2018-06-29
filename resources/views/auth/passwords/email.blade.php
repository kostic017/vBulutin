@extends('layouts.auth')

@section('content')
    <div class="card-header">
        {{ __('auth.reset-password') }}
    </div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form id="captcha-form" method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('auth.email') }}</label>
                <div class="col-md-6">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">
                    @include('includes.error', ['error_key' => 'email'])
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    @include('includes.recaptcha', ['recaptcha_action' => __('auth.reset-password')])
                </div>
            </div>
        </form>
    </div>
@endsection
