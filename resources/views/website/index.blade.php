@extends('layouts.website')

@section('content')
    @if ($boards->isEmpty())
        Niko još nije napravio forum.
    @else
        @foreach ($boards as $key => $board)
            @if ($board->is_visible)
                <div class="board">
                    <h4>{{ $board->title }}</h4>
                    <p>{{ $board->description }}</a></p>
                    <p><a href="{{ route('front.index', ['board_name' => $board->name]) }}/">Poseti forum</a></p>
                </div>
            @endif
        @endforeach
    @endif
@stop
