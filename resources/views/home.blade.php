@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('generic.welcome') }}
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        {{ __('auth.logged-in') }}
                        <ul class="home-links">
                            <li><a href="{{ route('public.index') }}">Početna stranica</a></li>
                            @if (Auth::user()->is_admin)
                                <li><a href="{{ route('admin.index') }}">Administratorski panel</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
