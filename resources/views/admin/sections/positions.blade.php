@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('lib/jquery-ui/jquery-ui.min.css') }}">
@stop

@section('scripts')
    <script src="{{ asset('lib/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/jquery-nestable/jquery.nestable.min.js') }}"></script>
    <script>$(() => { positions(); });</script>
@stop

@section('content')
    @include('admin.includes.overlay')

    <div class="positioning-buttons">
        <div>
            <form action="" method="post">
                <button type="button" class="btn btn-primary" name="save">{{ __('admin.save') }}</button>
            </form>
        </div>
        <div class="categories-tree-controls collapse-buttons">
            <button type="button" class="btn">-</button>
            <button type="button" class="btn">+</button>
        </div>
    </div>

    <div class="sortable-categories collapse-buttons">
        @foreach ($categories as $category)
            <div class="sortable-category">

                <div class="category-header {{ $category->deleted_at ? 'trashed' : '' }}">
                    <div>
                        <button class="category-tree-control" data-action="collapse"></button>
                        ({{ $category->id }}) {{ $category->title }}
                    </div>
                    <div class="forums-tree-controls">
                        <button type="button" class="btn">-</button>
                        <button type="button" class="btn">+</button>
                    </div>
                </div>

                <div class="dd" data-categoryid="{{ $category->id }}">
                    <ol class="dd-list">
                        @foreach ($category["forums"] as $parentForum)
                           <li class="dd-item" data-id="{{ $parentForum->id }}">
                                <div class="dd-handle {{ $parentForum->deleted_at ? 'trashed' : '' }}">
                                    ({{ $parentForum->id }}) {{ $parentForum->title }}
                                </div>
                                @if (count($parentForum["children"]) > 0)
                                    <ol class="dd-list">
                                        @foreach ($parentForum["children"] as $childForum)
                                            <li class="dd-item" data-id="{{ $childForum->id }}">
                                                <div class="dd-handle  {{ $childForum->deleted_at ? 'trashed' : '' }}">
                                                    ({{ $childForum->id }}) {{ $childForum->title }}
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

    <span class="d-none" data-key="generic.error">{{ __('generic.error') }}</span>
    <span class="d-none" data-key="admin.positions-success">{{ __('admin.positions-success') }}</span>
@stop

