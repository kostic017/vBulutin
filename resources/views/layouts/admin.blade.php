@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/board-admin.css') }}">
    @yield('styles')
@overwrite

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <nav>
                    <ul class="list-group">
                        <li class="list-group-item {{ active_class(if_route('admin.index')) }}">
                            <a href="{{ route('admin.index', [request()->route('board_address')]) }}">Početna</a>
                        </li>
                        <li class="list-group-item {{ active_class(if_route('forums.index')) }}">
                            <a href="{{ route('forums.index', [request()->route('board_address')]) }}">Kategorije i forumi</a>
                        </li>
                        <li class="list-group-item {{ active_class(if_route('users.index.admin')) }}">
                            <a href="{{ route('users.index.admin', [request()->route('board_address')]) }}">Korisnici</a>
                        </li>
                        <li class="list-group-item {{ active_class(if_route('reports.index')) }}">
                            <a href="{{ route('reports.index', [request()->route('board_address')]) }}">Izveštaji</a>
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

