@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <strong>{{ $forum->title }}</strong>
        </div>
        <div class="card-body">

            <table class="table table-striped show">
                <tr>
                    <td class="title">ID</td>
                    <td class="content">{{ $forum->id }}</td>
                </tr>
                <tr>
                    <td class="title">{{ __('db.slug') }}</td>
                    <td class="content">{{ $forum->slug }}</td>
                </tr>
                <tr>
                    <td class="title">{{ __('db.description') }}</td>
                    <td class="content"><div>{!! $forum->description ? BBCode::parse($forum->description) : '-' !!}</div></td>
                </tr>
                <tr>
                    <td class="title">{{ __('db.position') }}</td>
                    <td class="content">{{ $forum->position }}</td>
                </tr>
                <tr>
                    <td class="title">{{ __('db.locked') }}</td>
                    <td class="content">{{ $forum->is_locked ? 'da' : 'ne' }}</td>
                </tr>
                <tr>
                    <td class="title">{{ __('db.category') }}</td>
                    <td class="content"><a href="{{ route('categories.show.admin', [$category->id]) }}">{{ $category->title }}</a></td>
                </tr>
                <tr>
                    <td class="title">{{ __('db.parent_forum') }}</td>
                    <td class="content">
                        @if ($parentForum)
                            <a href="{{ route('forums.show.admin', [$parentForum->id]) }}">{{ $parentForum->title }}</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="title">{{ __('db.trashed') }}</td>
                    <td class="content">{{ $forum->deletedAt ?? '-' }}</td>
                </tr>
            </table>

            @php ($row = $forum)
            @php ($table = ['singular' => 'forum', 'plural' => 'forums'])
            @include ('admin.includes.show-buttons')

        </div>
    </div>
@stop
