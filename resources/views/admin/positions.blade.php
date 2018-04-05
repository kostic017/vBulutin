@extends("admin.base")

@section("title")
    Pozicioniranje
@stop

@section("more-content")
    <p id="message">Snimanje uspešno izvršeno.</p>

    <div class="positioning-buttons">
        <div>
            <form action="" method="post">
                <button type="button" name="save">Sačuvaj</button>
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

    <!-- Localization Helpers -->
    <span class="hidden" data-lngkey="messages.error">{{ __('messages.error') }}</span>
    <span class="hidden" data-lngkey="messages.success">{{ __("messages.success") }}</span>
@stop

@section("more-scripts")
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-nestable@0.8.0/jquery.nestable.min.js"></script>
@stop