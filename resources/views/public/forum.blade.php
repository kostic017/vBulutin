@extends('layouts.public')

@section('content')
    <div class="top-box">
        <ul class="path">
            <li><a href="{{ route('website.index') }}">{{ config('app.name') }}</a></li>
            <li><a href="{{ route('directories.show', [$board->directory->slug]) }}">{{ $board->directory->title }}</a></li>
            <li><a href="{{ route('boards.show', [$board->address]) }}">{{ $board->title }}</a></li>
            <li><a href="{{ route_category_show($forum->category) }}">{{ $forum->category->title }}</a></li>
            @if ($forum->parent)
                <li><a href="{{ route_forum_show($forum->parent) }}">{{ $forum->parent->title }}</a></li>
            @endif
        </ul>
        <div class="page-info">
            <h2>{{ $forum->title }}</h2>
            @if ($forum->description)
                <p class="desc">{!! BBCode::parse($forum->description) !!}</p>
            @endif
        </div>
    </div>

    @if ($is_admin || !$forum->is_locked)
        <div class="topbox-actions">
            @if (!$forum->is_locked)
                <p><a href="#scform">Otvori temu</a></p>
            @endif
            @if ($is_admin)
                <form action="{{ route('forums.lock', [$board->address, $forum->id]) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-{{ $forum->is_locked ? 'success' : 'danger' }}">
                        {{ $forum->is_locked ? 'Otključaj' : 'Zaključaj' }} forum
                    </button>
                </form>
            @endif
        </div>
    @endif

    @if ($child_forums->count())
        <table class="main-table table-hover">
            <caption>
                <div class="flex-center-xy">
                    Potforumi
                    <a href="#top" class="back2top" title="Top">Top</a>
                </div>
            </caption>
            @foreach ($child_forums as $_child_forum)
                @include('public.includes.table-row', ['row' => $_child_forum, 'row_type' => 'forum'])
            @endforeach
        </table>
    @endif

    @if ($topics->isEmpty())
        <p>Nema tema u ovom forumu.</p>
    @else
        {{ $topics->links() }}
        <table class="main-table table-hover">
            <caption>
                <div class="flex-center-xy">
                    Teme
                    <a href="#top" class="back2top" title="Top">Top</a>
                </div>
            </caption>
            @foreach ($topics as $_topic)
                @include('public.includes.table-row', ['row' => $_topic, 'row_type' => 'topic'])
            @endforeach
        </table>
        {{ $topics->links() }}
    @endif

    @if ($forum->is_locked)
        <p>Forum je zaključan, te nije moguće otvarati nove teme.</p>
    @elseif (Auth::check())
        <form action="{{ route('topics.store') }}" method="post" id="scform">
            @csrf
            <input type="hidden" name="forum_id" value="{{ $forum->id }}">
            <div class="form-group">
                <label for="title">Naslov</label>
                <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}">
                @include('includes.error', ['error_key' => 'title'])
            </div>
            <div class="form-group">
                <label for="sceditor" class="sr-only">Poruka</label>
                <textarea id="sceditor" id="content" name="content" class="{{ $errors->has('content') ? ' is-invalid' : '' }}">{{ old('content') }}</textarea>
                @include('includes.error', ['error_key' => 'content'])
            </div>
            <div class="form-group text-center">
                <button class="btn btn-success" type="submit">Otvori temu</button>
            </div>
        </form>
        @include('includes.sceditor')
    @else
        <p>Samo prijavljeni korisnici mogu otvarati nove teme.</p>
    @endif
@stop
