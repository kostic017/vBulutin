@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/jquery-ui/jquery-ui.min.css') }}">
@stop

@section('content')
    @include('includes.overlay')

    <div class="toplevel-buttons">
        <form action="" method="post">
            <button type="button" class="btn btn-primary" name="save">Sačuvaj pozicije</button>
        </form>
        <div class="actions">
            <a href="{{ route('categories.create', [request()->route('board_address')]) }}" class="btn" title="Nova kategorija"><i class="fas fa-file"></i></a>
            <button class="btn" title="Skupi sve kategorije"><i class="fas fa-minus"></i></button>
            <button class="btn" title="Raširi sve kategorije"><i class="fas fa-plus"></i></button>
        </div>
    </div>

    @if ($categories->isEmpty())
        Trenutno nema ničeg ovde...
    @else
        <div class="sortable-categories collapse-buttons">
            @foreach ($categories as $category)
                <div class="sortable-category">

                    <div class="category-header">
                        <div class="title{{ $category->deleted_at ? ' trashed' : '' }}">
                            <button class="tree-control" data-action="collapse">
                                <span class="btn-minus"><i class="fas fa-minus"></i></span>
                                <span class="btn-plus"><i class="fas fa-plus"></i></span>
                            </button>
                            ({{ $category->id }}) {{ $category->title }}
                        </div>
                        <div class="actions">
                            <a href="{{ route('categories.show.admin', [request()->route('board_address'), $category->id]) }}" class="btn" title="Pregledaj kategoriju"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('forums.create', [request()->route('board_address'), 'category', $category->id]) }}" class="btn" title="Novi forum"><i class="fas fa-file"></i></a>
                            <a href="{{ route('categories.edit', [request()->route('board_address'), $category->id]) }}" class="btn" title="Izmeni kategoriju"><i class="fas fa-pencil-alt"></i></a>
                            <button type="button" class="btn" title="Obriši kategoriju"><i class="fas fa-eraser"></i></button>
                            <button type="button" class="btn" title="Skupi sve forume"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn" title="Raširi sve forume"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="dd" data-categoryid="{{ $category->id }}">
                        <ol class="dd-list">
                            @foreach ($category["parent_forums"] as $parent_forum)
                               <li class="dd-item" data-id="{{ $parent_forum->id }}">
                                    <div class="dd-handle {{ $parent_forum->deleted_at ? 'trashed' : '' }}">
                                        ({{ $parent_forum->id }}) {{ $parent_forum->title }}
                                    </div>
                                    <div class="actions">
                                        <a href="{{ route('forums.show.admin', [request()->route('board_address'), $parent_forum->id]) }}" class="btn" title="Pregledaj forum"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('forums.create', [request()->route('board_address'), 'parent_forum', $parent_forum->id]) }}" class="btn" title="Novi potforum"><i class="fas fa-file"></i></a>
                                        <a href="{{ route('forums.edit', [request()->route('board_address'), $parent_forum->id]) }}" class="btn" title="Izmeni forum"><i class="fas fa-pencil-alt"></i></a>
                                        <button type="button" class="btn" title="Obriši forum"><i class="fas fa-eraser"></i></button>
                                    </div>
                                    @if (count($parent_forum['child_forums']))
                                        <ol class="dd-list">
                                            @foreach ($parent_forum['child_forums'] as $child_forum)
                                                <li class="dd-item" data-id="{{ $child_forum->id }}">
                                                    <div class="dd-handle {{ $child_forum->deleted_at ? ' trashed' : '' }}">
                                                        <div>
                                                            ({{ $child_forum->id }}) {{ $child_forum->title }}
                                                        </div>
                                                        <div class="actions">
                                                            <button type="button" class="btn" title="Izmeni forum"><i class="fas fa-pencil-alt"></i></button>
                                                            <button type="button" class="btn" title="Obriši forum"><i class="fas fa-eraser"></i></button>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ol>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    <span class="d-none" data-key="generic.error">{{ __('generic.error') }}</span>
    <span class="d-none" data-key="admin.positions-success">{{ __('admin.positions-success') }}</span>

    <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-nestable/jquery.nestable.min.js') }}"></script>
    <script src="{{ asset('js/nestable.js') }}"></script>
@stop

