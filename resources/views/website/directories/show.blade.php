@extends('layouts.website')

@section('content')
    <div class="card-header card-header-directory">
        <div>
            <h2>{{ $directory->title }}</h2>
            <p>{{ $directory->description }}</p>
        </div>
        <div class="button">
            <a class="btn btn-primary" id="create-forum" href="{{ Auth::check() ? route('admin.boards.create', ['slug' => $directory->slug]) : "#" }}" role="button">Napravi svoj forum</a>
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
                    <p><a href="{{ route('public.show', ['url' => $board->url]) }}/">Poseti forum</a></p>
                </div>
            @endforeach
        @endif
    </div>

    @if (!Auth::check())
        <script>
            $(function() {
                $('#create-forum').click(function(e){
                    e.stopPropagation();
                    const dropdown = $("#dropdown-login");
                    if ($('.dropdown-menu', dropdown).is(":hidden")){
                        $('.dropdown-toggle', dropdown).dropdown('toggle');
                    }
                });
            });
        </script>
    @endif
@stop

