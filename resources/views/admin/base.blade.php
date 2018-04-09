@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/general.css') }}">
    @yield('more-styles')
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <nav>
                    <ul class="list-group">
                        <li class="list-group-item {{ active_class(if_uri(['admin'])) }}">
                            <a href="/admin/">{{ __('Home') }}</a>
                        </li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item {{ active_class(if_uri_pattern('admin/sections*')) }}">
                            <a href="{{ route('sections.index') }}">{{ __('Sections') }}</a>
                        </li>
                        <li class="list-group-item {{ active_class(if_uri_pattern('admin/forums*')) }}">
                            <a href="{{ route('forums.index') }}">{{ __('Forums') }}</a>
                        </li>
                        <li class="list-group-item  {{ active_class(if_route('admin.positions')) }}">
                            <a href="{{ route('admin.positions') }}">{{ __('Positioning') }}</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-8 col-lg-9">
                @yield('more-content')
            </div>
        </div>
    </div>
@stop

@section('scripts')
    @yield('more-scripts')
@stop
