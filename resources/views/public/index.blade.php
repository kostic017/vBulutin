@extends('layouts.public')

@section('content')
    <div class="top-box mb-3">
        <ul class="path">
            <li><a href="{{ route('website.index') }}">{{ config('app.name') }}</a></li>
            <li><a href="{{ route('directories.show', [$board->directory->slug]) }}">{{ $board->directory->title }}</a></li>
        </ul>
    </div>

    @if ($categories->isEmpty())
        Trenutno nema ničeg ovde...
    @else
        @foreach ($categories as $category)
            @include('public.includes.table-category')
        @endforeach
    @endif
@stop
