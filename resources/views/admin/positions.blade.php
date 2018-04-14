@extends('admin.base')

@section('styles')
    <link rel="stylesheet" href="{{ asset('lib/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/positions.css') }}">
@stop

@section('scripts')
    <script src="{{ asset('lib/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/jquery-nestable/jquery.nestable.min.js') }}"></script>
    <script src="{{ asset('js/admin/positions.js') }}"></script>
@stop

@section('content')
    <div class="positioning-buttons">
        <div>
            <form action="" method="post">
                <button type="button" name="save">{{ __('buttons.save') }}</button>
            </form>
        </div>
        <div class="categories-tree-controls collapse-buttons">
            <button type="button">-</button>
            <button type="button">+</button>
        </div>
    </div>

    <div class="sortable-categories collapse-buttons">
        @foreach ($categories as $category)
            <div class="sortable-category">

                <div class="category-header">
                    <div>
                        <button class="category-tree-control" data-action="collapse"></button>
                        ({{ $category["id"] }}) {{ $category["title"] }}
                    </div>
                    <div class="forums-tree-controls">
                        <button type="button">-</button>
                        <button type="button">+</button>
                    </div>
                </div>

                <div class="dd" data-categoryid="{{ $category["id"] }}">
                    <ol class="dd-list">
                        @foreach ($category["forums"] as $parentForum)
                           <li class="dd-item" data-id="{{ $parentForum["id"] }}">
                                <div class="dd-handle">
                                    ({{ $parentForum["id"] }}) {{ $parentForum["title"] }}
                                </div>
                                @if (count($parentForum["children"]) > 0)
                                    <ol class="dd-list">
                                        @foreach ($parentForum["children"] as $childForum)
                                            <li class="dd-item" data-id="{{ $childForum["id"] }}">
                                                <div class="dd-handle">
                                                    ({{ $childForum["id"] }}) {{ $childForum["title"] }}
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
@stop

