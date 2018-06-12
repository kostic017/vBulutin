@extends('layouts.website')

@section('content')
    @if ($boards->isEmpty())
        Nema boards.
    @else
        @foreach ($boards as $board)
            <p><a href="/{{ $board->name }}/">{{ $board->title }}</a></p>
        @endforeach
    @endif
@stop
