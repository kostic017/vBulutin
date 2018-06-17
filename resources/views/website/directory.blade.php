@extends('layouts.website')

@section('content')
    <div class="card-header directory">
        <div>
            <h2>{{ $directory->title }}</h2>
            <p>{{ $directory->description }}</p>
        </div>
        <div class="button">
            <button type="button" class="btn btn-primary">Napravi svoj forum</button>
        </div>
    </div>
    <div class="card-body">
        @if ($boards->isEmpty())
            <p>Još nema foruma u ovom direktorijumu.</p>
        @else
            @foreach ($boards as $board)
                <div class="board">
                    <h4>{{ $board->title }}</h4>
                    <p>{{ $board->description }}</a></p>
                    <p><a href="{{ route('front.index', ['board_name' => $board->name]) }}/">Poseti forum</a></p>
                </div>
            @endforeach
        @endif
    </div>
@stop

