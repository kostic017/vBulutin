@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <strong>Forum: {{ $forum->title }}</strong>
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
                    <td class="content"><a href="{{ route('categories.show.admin', [request()->route('board_address'), $forum->category->slug]) }}">{{ $forum->category->title }}</a></td>
                </tr>
                <tr>
                    <td class="title">{{ __('db.parent_forum') }}</td>
                    <td class="content">
                        @if ($forum->parent)
                            <a href="{{ route('forums.show.admin', [request()->route('board_address'), $forum->parent->slug]) }}">{{ $forum->parent->title }}</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="title">{{ __('db.trashed') }}</td>
                    <td class="content">{{ $forum->deleted_at ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
@stop
