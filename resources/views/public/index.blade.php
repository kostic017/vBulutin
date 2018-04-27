@extends('layouts.public')

@section('content')
    @foreach ($categories as $category)
        @include('public.includes.table_category')
    @endforeach
@stop
