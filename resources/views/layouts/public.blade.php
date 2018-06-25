@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/board-public.css') }}">
    @yield('styles')
@overwrite

@section('content')
    <section class="content">
       @yield('content')
    </section>
@overwrite
