@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/jquery-ui/jquery-ui.min.css') }}">
@stop

@section('content')
    @include('includes.overlay')
    <div class="sections-index">
        <div class="toplevel-buttons">
            <div>
                @if ($categories->count())
                    <button type="button" class="btn btn-primary" name="save">Sačuvaj pozicije</button>
                @else
                    Trenutno nema ničeg ovde...
                @endif
            </div>
            <div class="actions">
                <a href="{{ route('categories.create', [$board->address]) }}" class="btn" title="Nova kategorija"><i class="fas fa-file-alt"></i></a>
                <button class="btn collapse-categories minus" title="Skupi sve kategorije"><i class="fas fa-minus"></i></button>
                <button class="btn collapse-categories plus" title="Raširi sve kategorije"><i class="fas fa-plus"></i></button>
            </div>
        </div>

        @if ($categories->count())
            <div class="sortable-categories collapse-buttons">
                @foreach ($categories as $_category)
                    <div class="sortable-category">

                        <div class="category-header">
                            <div class="title{{ $_category->trashed() ? ' trashed' : '' }}">
                                <button class="collapse-category" data-action="collapse">
                                    <span class="minus"><i class="fas fa-minus"></i></span>
                                    <span class="plus"><i class="fas fa-plus"></i></span>
                                </button>
                                ({{ $_category->id }}) {{ $_category->title }}
                            </div>
                            <div class="actions">
                                <a href="{{ route('categories.show.admin', [$board->address, $_category->slug]) }}" class="btn" title="Pregledaj kategoriju"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('forums.create', [$board->address, 'category', $_category->id]) }}" class="btn" title="Novi forum"><i class="fas fa-file-alt"></i></a>
                                <a href="{{ route('categories.edit', [$board->address, $_category->slug]) }}" class="btn" title="Izmeni kategoriju"><i class="fas fa-pencil-alt"></i></a>
                                @if ($_category->trashed())
                                    <form class="d-inline-block" method="post" action="{{ route('categories.restore', [$board->address, $_category->id]) }}">
                                        @csrf
                                        <button type="submit" class="btn" title="Vrati kategoriju"><i class="fas fa-history"></i></button>
                                    </form>
                                @else
                                    <form class="d-inline-block" method="post" action="{{ route('categories.destroy', [$board->address, $_category->id]) }}">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn" title="Obriši kategoriju"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                @endif
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
                                            <div class="dd-handle {{ $_parent_forum->trashed() ? 'trashed' : '' }}">
                                                ({{ $_parent_forum->id }}) {{ $_parent_forum->title }}
                                            </div>
                                            <div class="actions">
                                                <a href="{{ route('forums.show.admin', [$board->address, $_parent_forum->slug]) }}" class="btn" title="Pregledaj forum"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('forums.create', [$board->address, 'parent_forum', $_parent_forum->id]) }}" class="btn" title="Novi potforum"><i class="fas fa-file-alt"></i></a>
                                                <a href="{{ route('forums.edit', [$board->address, $_parent_forum->slug]) }}" class="btn" title="Izmeni forum"><i class="fas fa-pencil-alt"></i></a>
                                                @if ($_parent_forum->trashed())
                                                    <form class="d-inline-block" method="post" action="{{ route('forums.restore', [$board->address, $_parent_forum->id]) }}">
                                                        @csrf
                                                        <button type="submit" class="btn" title="Vrati forum"><i class="fas fa-history"></i></button>
                                                    </form>
                                                @else
                                                    <form class="d-inline-block" method="post" action="{{ route('forums.destroy', [$board->address, $_parent_forum->id]) }}">
                                                        @csrf
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit" class="btn" title="Obriši forum"><i class="fas fa-trash-alt"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                            @if (count($_parent_forum['child_forums']))
                                                <ol class="dd-list">
                                                    @foreach ($_parent_forum['child_forums'] as $_child_forum)
                                                        <li class="dd-item" data-id="{{ $_child_forum->id }}">
                                                            <div class="dd-handle {{ $_child_forum->trashed() ? ' trashed' : '' }}">
                                                                ({{ $_child_forum->id }}) {{ $_child_forum->title }}
                                                            </div>
                                                            <div class="actions">
                                                                <a href="{{ route('forums.show.admin', [$board->address, $_child_forum->slug]) }}" class="btn" title="Pregledaj forum"><i class="fas fa-eye"></i></a>
                                                                <a href="{{ route('forums.edit', [$board->address, $_child_forum->slug]) }}" class="btn" title="Izmeni forum"><i class="fas fa-pencil-alt"></i></a>
                                                                @if ($_child_forum->trashed())
                                                                    <form class="d-inline-block" method="post" action="{{ route('forums.restore', [$board->address, $_child_forum->id]) }}">
                                                                        @csrf
                                                                        <button type="submit" class="btn" title="Vrati forum"><i class="fas fa-history"></i></button>
                                                                    </form>
                                                                @else
                                                                    <form class="d-inline-block" method="post" action="{{ route('forums.destroy', [$board->address, $_child_forum->id]) }}">
                                                                        @csrf
                                                                        {{ method_field('DELETE') }}
                                                                        <button type="submit" class="btn" title="Obriši forum"><i class="fas fa-trash-alt"></i></button>
                                                                    </form>
                                                                @endif
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
    </div>

    <span class="d-none" data-key="generic.error">{{ __('generic.error') }}</span>

    <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-nestable/jquery.nestable.min.js') }}"></script>
    <script src="{{ asset('js/nestable.js') }}"></script>
@stop

