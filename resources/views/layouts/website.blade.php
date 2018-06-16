@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/website.css') }}">
    @yield('styles')
@overwrite

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <nav>
                    <ul class="list-group">
                        <li class="list-group-item {{ active_class(if_route_param('slug', 'all')) }}">
                            <a href="{{ route('website.boardcats', ['slug' => 'all']) }}">Svi</a>
                        </li>
                        @foreach ($board_categories as $board_category)
                            <li class="list-group-item {{ active_class(if_route_param('slug', $board_category->slug)) }}">
                                <a href="{{ route('website.boardcats', ['slug' => $board_category->slug]) }}">{{ $board_category->title }}</a>
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
        </div>
    </div>
@overwrite
