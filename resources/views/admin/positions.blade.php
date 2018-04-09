@extends("admin.base")

@section("more-styles")
    <link rel="stylesheet" href="{{ asset('css/admin/positions.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/jquery-ui/jquery-ui.min.css') }}">
@stop

@section("more-content")
    <div class="positioning-buttons">
        <div>
            <form action="" method="post">
                <button type="button" name="save">{{ __('Save') }}</button>
            </form>
        </div>
        <div class="sections-tree-controls collapse-buttons">
            <button type="button">-</button>
            <button type="button">+</button>
        </div>
    </div>

    <div class="sortable-sections collapse-buttons">
        @foreach ($sections as $section)
            <div class="sortable-section">

                <div class="section-header">
                    <div>
                        <button class="section-tree-control" data-action="collapse"></button>
                        ({{ $section["id"] }}) {{ $section["title"] }}
                    </div>
                    <div class="forums-tree-controls">
                        <button type="button">-</button>
                        <button type="button">+</button>
                    </div>
                </div>

                <div class="dd" data-sectionid="{{ $section["id"] }}">
                    <ol class="dd-list">
                        @foreach ($section["forums"] as $parentForum)
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

@section("more-scripts")
    <script src="{{ asset('lib/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/jquery-nestable/jquery.nestable.min.js') }}"></script>
    <script src="{{ asset("js/admin/positions.js") }}"></script>
@stop
