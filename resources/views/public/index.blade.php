@extends('layouts.public')

@section('content')
    @if ($categories->isEmpty())
        <p>Trenutno nema ničeg ovde...</p>
    @else
        @foreach ($categories as $self)
            @include('public.includes.table_category')
        @endforeach
    @endif
@stop
