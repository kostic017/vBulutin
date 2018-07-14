@extends('layouts.website')

@section('content')
    <div class="card">
        <div class="card-header card-header-directory">
            <div>
                <h2>{{ $directory->title }}</h2>
                <p>{!! BBCode::parse($directory->description) !!}</p>
            </div>
            <div class="button">
                <a class="btn btn-primary" id="create-forum" href="{{ Auth::check() ? route('boards.create', [$directory->slug]) : "#" }}" role="button">Napravi svoj forum</a>&nbsp;
                @if (($user = Auth::user()) && $user->is_master)
                    <a class="btn btn-success" href="{{ route('directories.edit', [$directory->slug]) }}">Izmeni direktorijum</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @php($flag = false)
            @foreach ($directory->boards as $_board)
                @if ($_board->is_visible || $_board->is_admin())
                    @php($flag = true)
                    <div class="board">
                        <h4>{{ $_board->title }}</h4>
                        @if (is_not_empty($_board->description))
                            <p>{!! BBCode::parse($_board->description) !!}</a></p>
                        @endif
                        <a href="{{ route('boards.show', [$_board->address]) }}/">Poseti forum</a> {{ !$_board->is_visible ? '(sakriven)' : '' }}
                    </div>
                @endif
            @endforeach
            @if (!$flag)
                Još nema foruma u ovom direktorijumu.
            @endif
        </div>

        @if (!Auth::check())
            <script>
                $(function() {
                    $("#create-forum").click(function(e){
                        e.stopPropagation();
                        $(".navbar-collapse").collapse("show");
                        const dropdown = $("#dropdown-login");
                        if ($(".dropdown-menu", dropdown).is(":hidden")){
                            $(".dropdown-toggle", dropdown).dropdown("toggle");
                            $("#login-email").focus();
                        }
                    });
                });
            </script>
        @endif
    </div>
@stop

