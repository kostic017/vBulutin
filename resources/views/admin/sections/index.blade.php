@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/jquery-ui/jquery-ui.min.css') }}">
@stop

@section('content')
    @include('includes.overlay')

    <div class="toplevel-buttons">
        <div>
            @if ($categories->count())
                <button type="button" class="btn btn-primary" name="save">Sačuvaj pozicije</button>
            @else
                Trenutno nema ničeg ovde...
            @endif
        </div>
        <div class="actions">
            <a href="{{ route('categories.create', [$board->address]) }}" class="btn" title="Nova kategorija"><i class="fas fa-file"></i></a>
            <button class="btn collapse-categories minus" title="Skupi sve kategorije"><i class="fas fa-minus"></i></button>
            <button class="btn collapse-categories plus" title="Raširi sve kategorije"><i class="fas fa-plus"></i></button>
        </div>
    </div>

    @if ($categories->count())
        <div class="sortable-categories collapse-buttons">
            @foreach ($categories as $_category)
                <div class="sortable-category">

                    <div class="category-header">
                        <div class="title{{ $_category->deleted_at ? ' trashed' : '' }}">
                            <button class="collapse-category" data-action="collapse">
                                <span class="minus"><i class="fas fa-minus"></i></span>
                                <span class="plus"><i class="fas fa-plus"></i></span>
                            </button>
                            ({{ $_category->id }}) {{ $_category->title }}
                        </div>
                        <div class="actions">
                            <a href="{{ route('categories.show.admin', [$board->address, $_category->slug]) }}" class="btn" title="Pregledaj kategoriju"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('forums.create', [$board->address, 'category', $_category->id]) }}" class="btn" title="Novi forum"><i class="fas fa-file"></i></a>
                            <a href="{{ route('categories.edit', [$board->address, $_category->slug]) }}" class="btn" title="Izmeni kategoriju"><i class="fas fa-pencil-alt"></i></a>
                            <button type="button" class="btn" title="Obriši kategoriju"><i class="fas fa-eraser"></i></button>
                            <button type="button" class="btn collapse-forums minus" title="Skupi sve forume"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn collapse-forums plus" title="Raširi sve forume"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="dd" data-categoryid="{{ $_category->id }}">
                        @if (!count($_category["parent_forums"]))
                            <div class="dd-empty"></div>
                        @else
                            <ol class="dd-list">
                                @foreach ($_category["parent_forums"] as $_parent_forum)
                                   <li class="dd-item" data-id="{{ $_parent_forum->id }}">
                                        <div class="dd-handle {{ $_parent_forum->deleted_at ? 'trashed' : '' }}">
                                            ({{ $_parent_forum->id }}) {{ $_parent_forum->title }}
                                        </div>
                                        <div class="actions">
                                            <a href="{{ route('forums.show.admin', [$board->address, $_parent_forum->slug]) }}" class="btn" title="Pregledaj forum"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('forums.create', [$board->address, 'parent_forum', $_parent_forum->id]) }}" class="btn" title="Novi potforum"><i class="fas fa-file"></i></a>
                                            <a href="{{ route('forums.edit', [$board->address, $_parent_forum->slug]) }}" class="btn" title="Izmeni forum"><i class="fas fa-pencil-alt"></i></a>
                                            <button type="button" class="btn" title="Obriši forum"><i class="fas fa-eraser"></i></button>
                                        </div>
                                        @if (count($_parent_forum['child_forums']))
                                            <ol class="dd-list">
                                                @foreach ($_parent_forum['child_forums'] as $child_forum)
                                                    <li class="dd-item" data-id="{{ $child_forum->id }}">
                                                        <div class="dd-handle {{ $child_forum->deleted_at ? ' trashed' : '' }}">
                                                            <div>
                                                                ({{ $child_forum->id }}) {{ $child_forum->title }}
                                                            </div>
                                                            <div class="actions">
                                                                <a href="{{ route('forums.show.admin', [$board->address, $child_forum->slug]) }}" class="btn" title="Pregledaj forum"><i class="fas fa-eye"></i></a>
                                                                <a href="{{ route('forums.edit', [$board->address, $child_forum->slug]) }}" class="btn" title="Izmeni forum"><i class="fas fa-pencil-alt"></i></a>
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
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    <span class="d-none" data-key="generic.error">{{ __('generic.error') }}</span>

    <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-nestable/jquery.nestable.min.js') }}"></script>
    <script src="{{ asset('js/nestable.js') }}"></script>
@stop

