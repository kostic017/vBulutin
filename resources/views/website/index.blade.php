@extends('layouts.website')

@section('content')
    @if ($boards->isEmpty())
        <p>Budi prvi ko je napravio forum.</p>
        <button type="button" class="btn btn-light">Napravi svoj forum</button>
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
