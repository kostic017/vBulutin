@extends('layouts.public')

@section('content')
    @foreach ($categories as $self)
        @include('public.includes.table_category')
    @endforeach
@stop
