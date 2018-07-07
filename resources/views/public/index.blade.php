@extends('layouts.public')

@section('content')
    @if ($categories->isEmpty())
        Trenutno nema ničeg ovde...
    @else
        @foreach ($categories as $category)
            @include('public.includes.table-category')
        @endforeach
    @endif
@stop
