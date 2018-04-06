@extends("layouts.app")

@section("styles")
    @yield("more-styles")
    <link rel="stylesheet" href="{{ asset("css/public.css") }}">
@stop

@section ("sidebar")

@stop

@section("content")
    @yield("more-content")
@stop

@section("scripts")
    @yield("more-scripts")
    <script src="{{ asset("js/public.js") }}"></script>
@stop
