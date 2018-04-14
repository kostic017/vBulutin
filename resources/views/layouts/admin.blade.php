@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/general.css') }}">
    @yield('styles')
@overwrite

@section('scripts')
    @yield('scripts')
@overwrite

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <nav>
                    <ul class="list-group">
                        <li class="list-group-item {{ active_class(if_uri(['admin'])) }}">
                            <a href="/admin/">{{ __('nav.home') }}</a>
                        </li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item {{ active_class(if_uri_pattern('admin/categories*')) }}">
                            <a href="{{ route('categories.index') }}">{{ __('nav.categories') }}</a>
                        </li>
                        <li class="list-group-item {{ active_class(if_uri_pattern('admin/forums*')) }}">
                            <a href="{{ route('forums.index') }}">{{ __('nav.forums') }}</a>
                        </li>
                        <li class="list-group-item  {{ active_class(if_route('admin.positions')) }}">
                            <a href="{{ route('admin.positions') }}">{{ __('nav.positioning') }}</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-8 col-lg-9">
                @yield('content')
            </div>
        </div>
    </div>
@overwrite

