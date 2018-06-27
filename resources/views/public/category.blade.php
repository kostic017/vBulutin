@extends('layouts.public')

@section('content')
    <div class="top-box">
        <div class="page-info">
            <h2>{{ $category->title }}</h2>
            @if ($category->description)
                <p class="desc">{!! BBCode::parse($category->description) !!}</p>
            @endif
        </div>
    </div>
    @include('public.includes.table_category')
@stop
