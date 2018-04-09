@extends('admin.base')

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>{{ $forum->title }}</strong>
        </div>

        <div class="card-body">
            {!! $forum->description !!}
        </div>

    </div>
@stop
