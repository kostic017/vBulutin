@extends('layouts.public')

@section('content')
    @foreach ($categories as $category)
        @include('board.public.includes.table_category')
    @endforeach
@stop
