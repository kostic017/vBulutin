@extends('layouts.public')

@section('content')
    <div class="top-box">
        <div class="page-info">
            <h2>{{ $self->title }}</h2>
            @if ($self->description)
                <p class="desc">{{ $self->description }}</p>
            @endif
        </div>
    </div>
    @include('public.includes.table_category')
@stop
