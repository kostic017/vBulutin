@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/website.css') }}">
    @yield('styles')
@overwrite

@section('nav-auth')
    @if (is_admin())
        <li><a class="nav-link" href="{{ route('back.index') }}">Admin panel</a></li>
    @endif
    @yield('nav-auth')
@overwrite

@section('scripts')
    @yield('scripts')
@overwrite

@section('content')
    <div class="container-fluid">
        <div class="row">
            @if ($boards->isEmpty())
                Niko nije napravio forum.
            @else
                <div class="col-md-4 col-lg-3">
                    <nav>
                        <ul class="list-group">
                            <li class="list-group-item {{ active_class(if_uri("/")) }}">
                                <a href="/">Svi</a>
                            </li>
                            @foreach ($boardCategories as $boardCategory)
                                <li class="list-group-item {{ active_class(if_uri_pattern("{$boardCategory->slug}")) }}">
                                    <a href="{{ "/{$boardCategory->slug}" }}">{{ $boardCategory->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
                <div class="col-md-8 col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            @yield('content')
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@overwrite
