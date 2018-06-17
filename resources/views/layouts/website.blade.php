@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/website.css') }}">
    @yield('styles')
@overwrite

@section('content')
    <div class="container-fluid">
        <div class="card">
            @yield('content')
        </div>
    </div>
@overwrite
