@extends('layouts.public')

@section('content')
    <div class="top-box">
        <ul class="path">
            <li><a href="{{ route('website.index') }}">{{ config('app.name') }}</a></li>
            <li><a href="{{ route('directories.show', [$board->directory->slug]) }}">{{ $board->directory->title }}</a></li>
            <li><a href="{{ route('boards.show', [$board->address]) }}">{{ $board->title }}</a></li>
        </ul>
        <div class="page-info">
            <h2>{{ $category->title }}</h2>
            @if ($category->description)
                <p class="desc">{!! BBCode::parse($category->description) !!}</p>
            @endif
        </div>
    </div>
    @include('public.includes.table-category')
@stop
