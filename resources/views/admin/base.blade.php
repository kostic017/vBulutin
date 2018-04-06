@extends('layouts.app')

@section('styles')
    @yield('more-styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <nav>
                    <ul class="list-group">
                        <li class="list-group-item {{ active_class(if_uri(['admin'])) }}">
                            <a href="/admin/">Početna</a>
                        </li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item {{ active_class(if_route('sections.index')) }}">
                            <a href="{{ route('sections.index') }}">Sekcije</a>
                        </li>
                        <li class="list-group-item {{ active_class(if_route('forums.index')) }}">
                            <a href="{{ route('forums.index') }}">Forumi</a>
                        </li>
                        <li class="list-group-item  {{ active_class(if_route('admin.positions')) }}">
                            <a href="{{ route('admin.positions') }}">Pozicioniranje</a>
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
    <script src="{{ asset('js/admin/general.js') }}"></script>
    @yield('more-scripts')
@stop
