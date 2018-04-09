@extends('admin.base')

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>{{ $section->title }}</strong>
        </div>

        <div class="card-body">
            {!! $section->description !!}
        </div>

    </div>
@stop
