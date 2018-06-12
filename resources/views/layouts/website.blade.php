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
    @yield('content')
@overwrite
