@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <strong>{{ $forum->title }}</strong>
        </div>
        <div class="card-body">

            <table class="table table-striped show">
                <tr>
                    <td>ID</td>
                    <td>{{ $forum->id }}</td>
                </tr>
                <tr>
                    <td>{{ __('db.slug') }}</td>
                    <td>{{ $forum->slug }}</td>
                </tr>
                <tr>
                    <td>{{ __('db.description') }}</td>
                    <td>
                        <div>{!! $forum->description ? BBCode::convertToHtml($forum->description) : '-' !!}</div>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('db.position') }}</td>
                    <td>{{ $forum->position }}</td>
                </tr>
                <tr>
                    <td>{{ __('db.locked') }}</td>
                    <td>{{ $forum->is_locked ? 'da' : 'ne' }}</td>
                </tr>
                <tr>
                    <td>{{ __('db.category') }}</td>
                    <td><a href="{{ route('categories.show', ['category' => $category->id]) }}">{{ $category->title }}</a></td>
                </tr>
                <tr>
                    <td>{{ __('db.parent_forum') }}</td>
                    <td>
                        @if ($parentForum)
                            <a href="{{ route('forums.show', ['forum' => $parentForum->id]) }}">{{ $parentForum->title }}</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>{{ __('db.trashed') }}</td>
                    <td>{{ $forum->deletedAt ?? '-' }}</td>
                </tr>
            </table>

            @php ($row = $forum)
            @php ($table = ['forum', 'forums'])
            @include ('admin.sections.includes.show-buttons')

        </div>
    </div>
@stop
